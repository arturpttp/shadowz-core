<?php

namespace Core\System\User;

use Core\Bases\Model;

class UserImpl implements User
{

    public ?int $id, $level = 0;
    public ?string $name, $lastname, $username, $email, $password;
    public array $errors = [];

    public static bool $isLogged = false;
    public static ?User $logged = null;

    private static Model $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        self::$model = $model;
    }

    /**
     * @inheritDoc
     */
    function load(): User
    {
        if (
            self::$model->database->contains('username', $this->username) and self::$model->database->contains('password', $this->password)
        ) {
            $query = self::$model->database->find([
                'username' => $this->username,
                'password' => $this->password
            ]);
            $user = $query->first();
            $this->id = $user['id'];
            $this->name = $user['name'];
            $this->lastname = $user['lastname'];
            $this->email = $user['email'];
            $this->level = $user['level'];
            self::$logged = $this;
            self::$isLogged = true;
        } else {
            $this->errors[] = "User $this->username doesn't exists";
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function get($id): ?User
    {
        if (self::$model->database->contains('id', $id)) {
            $query = self::$model->database->find([
                'id' => $id
            ]);
            $user = $query->first();
            $userObject = new UserImpl(self::$model);
            return $userObject->login($user['username'], $user['password']);
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function userExists($id): bool
    {
        return self::$model->database->contains('id', $id);
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return self::userExists($this->id);
    }

    /**
     * @inheritDoc
     */
    public static function create(string $name, string $lastname, string $username, string $email, string $password, Model $model, int $level = 1): User
    {
        $userObject = new UserImpl($model);
        $model->database->insert([
            'id' => 'NULL',
            'name' => $name,
            'lastname' => $lastname,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'level' => $level
        ]);
        return $userObject->login($username, $password);
    }

    /**
     * @inheritDoc
     */
    public function changeLevel($level): User
    {
        self::$model->database->update(['level' => $level], ['id' => $this->id]);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(): User
    {
        self::$model->database->delete(['id' => $this->id]);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function login($user, $password): User
    {
        $this->username = $user;
        $this->password = $password;
        $this->load();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function logout(): User
    {
        $this->id = null;
        $this->level = null;
        $this->name = null;
        $this->lastname = null;
        $this->username = null;
        $this->email = null;
        $this->password = null;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getLastName(): string
    {
        return $this->lastname;
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}