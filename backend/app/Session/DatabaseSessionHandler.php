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
        /** @var \Illuminate\Contracts\Auth\Authenticatable|null $user */
        $user = $this->user ?? null;

        if ($user instanceof Authenticatable) {
            $payload['object_type'] = get_class($user);
            $payload['object_id'] = $user->getAuthIdentifier();
        } else {
            $payload['object_type'] = null;
            $payload['object_id'] = null;
        }

        return $this;
    }
}
