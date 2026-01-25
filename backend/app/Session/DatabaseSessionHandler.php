<?php

declare(strict_types=1);

namespace App\Session;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;

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

        if ($user === null && $this->container !== null) {
            /** @var \Illuminate\Contracts\Auth\Guard|null $guard */
            $guard = $this->container->make('auth')->guard();
            if ($guard !== null) {
                $user = $guard->user();
            }
        }

        if ($user instanceof Authenticatable) {
            $payload['object_type'] = get_class($user);
            $payload['object_id'] = $user->getAuthIdentifier();
        } else {
            $payload['object_type'] = null;
            $payload['object_id'] = null;
        }

        $payload['app_name'] = Config::get('app.name');
        $payload['expires_at'] = Date::now()->addMinutes(Config::get('session.lifetime'))->timestamp;

        return $this;
    }
}
