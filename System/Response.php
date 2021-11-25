<?php

namespace Core\System;

use Core\Utils\Arrayable;

class Response implements Arrayable
{

    public int $code;
    public string $message;
    public bool $allowed;

    /**
     * @param int $code
     * @param string $message
     * @param bool $allowed
     */
    public function __construct(int $code, string $message, bool $allowed)
    {
        $this->code = $code;
        $this->message = $message;
        $this->allowed = $allowed;
    }

    public function toArray(): array {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'allowed' => $this->allowed,
        ];
    }

}