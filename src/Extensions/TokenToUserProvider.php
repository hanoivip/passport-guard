<?php 

namespace Hanoivip\PassportGuard\Extensions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Log;

class TokenToUserProvider implements UserProvider
{
    public function validateCredentials(Authenticatable $user, array $credentials)
    {}

    public function retrieveByToken($identifier, $token)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->get(config('passport.user'),[
            'headers' => ['Authorization' => 'Bearer ' . $token]
        ]);
        $user = json_decode($response->getBody(), true);
        Log::debug("Fetch user data:" . print_r($user, true));
        return $user;
    }

    public function retrieveByCredentials(array $credentials)
    {}

    public function retrieveById($identifier)
    {}

    public function updateRememberToken(Authenticatable $user, $token)
    {}

    
}