<?php 

namespace Hanoivip\PassportGuard\Extensions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Hanoivip\PassportGuard\Models\AppUser;

class TokenToUserProvider implements UserProvider
{
    public function __construct()
    {   
    }
    
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        Log::debug('TokenUserProvider...validateCredentials');
    }
    
    public function retrieveByToken($identifier, $token)
    {
        //Log::debug("TokenUserProvider retrieveByToken:" . $token);
        $key = md5($token);
        if (Cache::has($key))
        {
            $user = Cache::get($key);
            //Log::debug("TokenUserProvider found token in cache." . print_r($user, true));
            return $user;
        }
        else 
        {
            //Log::debug("TokenUserProvider not found token in cache. Fetching..");
            $user = new AppUser();
            $user->fetchUserByCredentials(['access_token' => $token]);
            /*
            $record = AppUser::find($user->getAuthIdentifier());
            if (empty($record))
            {
                $user->id = $user->getAuthIdentifier();
                $user->app_uid = rand(1000000, 100000000);
                $user->save();
            }*/
            Cache::put($key, $user, now()->addMinutes(30));
            return $user;
        }
    }

    /*public function retrieveByToken1($identifier, $token)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->get(config('passport.user'),[
            'headers' => ['Authorization' => 'Bearer ' . $token]
        ]);
        $user = json_decode($response->getBody(), true);
        Log::debug("Fetch user data:" . print_r($user, true));
        return $user;
    }*/

    public function retrieveByCredentials(array $credentials)
    {
        Log::debug('TokenUserProvider...retrieveByCredentials');
    }

    public function retrieveById($identifier)
    {
        Log::debug('TokenUserProvider...retrieveById');
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        Log::debug('TokenUserProvider...updateRememberToken');
    }

    
}