<?php

namespace App\Services;

use App\Models\GuestUser;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class OverwayService
{
    public function fetchUserFromAuthAPI(string $token): User|GuestUser
    {
        try {
            /** @var string */
            $overwayURL = config('overway.url');
            if (!$overwayURL) {
                throw new \Exception('Overway URL is not set');
            }

            /** @var array{object_id: string, object_type: string} */
            $response = Http::post($overwayURL.'/api/auth/administrator/validate-token', [
                'token' => $token,
            ])->json();

            if ($response['user_type'] === User::class) {
                return User::findOrFail($response['user_id']);
            } elseif ($response['user_type'] === GuestUser::class) {
                return GuestUser::findOrFail($response['user_id']);
            }

            throw new \Exception('Invalid user type');
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
            throw new \Exception('Failed to fetch user from Auth API');
        }
    }
}
