<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

class UserEmailAlreadyRegisteredException extends DomainException
{
    public function __construct()
    {
        parent::__construct('User email already registered.', self::HTTP_CONFLICT);
    }
}