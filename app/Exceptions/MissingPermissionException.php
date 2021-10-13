<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Arr;

class MissingPermissionException extends AuthorizationException
{
    /**
     * The permissions that the user did not have.
     *
     * @var array
     */
    protected $permissions;

    /**
     * Create a new missing permission exception.
     *
     * @param  array|string  $permissions
     * @param  string        $message
     *
     * @return void
     */
    public function __construct($permissions = [], $message = 'Invalid permission(s) provided.')
    {
        parent::__construct($message);

        $this->permissions = Arr::wrap($permissions);
    }

    /**
     * Get the permissions that the user did not have.
     *
     * @return array
     */
    public function permissions(): array
    {
        return $this->permissions;
    }
}
