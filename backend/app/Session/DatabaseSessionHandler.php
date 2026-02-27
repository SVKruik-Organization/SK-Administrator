<?php

declare(strict_types=1);

namespace App\Session;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;

class DatabaseSessionHandler extends BaseDatabaseSessionHandler
{
    /**
     * Add the user information to the session payload.
     *
     * @param  array<string, mixed>  $payload
     * @return $this
     */
    protected function addUserInformation(&$payload)
    {
        $payload['app_name'] = config('app.name');
        $user = $this->getAuthenticatedUser();

        if ($user instanceof Authenticatable) {
            $payload['object_type'] = get_class($user);
        } else {
            $payload['object_type'] = null;
        }

        return $this;
    }

    /**
     * Get the authenticated user from either guard.
     */
    protected function getAuthenticatedUser(): ?Authenticatable
    {
        if ($this->container === null) {
            return null;
        }

        /** @var \Illuminate\Contracts\Auth\Factory $auth */
        $auth = $this->container->make('auth');

        return $auth->guard('user')->user() ?? $auth->guard('guest')->user();
    }
}
