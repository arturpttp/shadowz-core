<?php

namespace Core\System\User;

use Core\Bases\Model;

interface User {



    /**
     * load user information
     *
     * @return User
     */
    function load(): User;

    /**
     * get user by $id, returns null if doesn't exists
     *
     * @param $id
     * @return User|null
     */
    public static function get($id): ?User;

    /**
     * check if user exists by $id
     *
     * @param $id
     * @return bool
     */
    public static function userExists($id): bool;

    /**
     * check if logged user really exists
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * create a user
     *
     * @param string $name
     * @param string $lastname
     * @param string $username
     * @param string $email
     * @param string $password
     * @param Model $model
     * @param int $level
     * @return User
     */
    public static function create(string $name, string $lastname, string $username, string $email, string $password, Model $model, int $level = 1): User;

    /**
     * change $level from logged user
     *
     * @param $level
     * @return User
     */
    public function changeLevel($level): User;

    /**
     * delete logged user
     *
     * @return User
     */
    public function delete(): User;

    /**
     * log in a user with $user and $password
     *
     * @param $user
     * @param $password
     * @return User
     */
    public function login($user, $password): User;

    /**
     * log out the logged user
     *
     * @return User
     */
    public function logout(): User;

    /**
     * @return int user's id in database
     */
    public function getId(): int;

    /**
     * @return string user's name
     */
    public function getName(): string;

    /**
     * @return string user's last name
     */
    public function getLastName(): string;

    /**
     * @return string user's username
     */
    public function getUsername(): string;

    /**
     * @return string user's email
     */
    public function getEmail(): string;

    /**
     * @return string user's password
     */
    public function getPassword(): string;

    /**
     * @return int user's system's level
     */
    public function getLevel(): int;
}