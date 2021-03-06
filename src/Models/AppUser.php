<?php
namespace Hanoivip\PassportGuard\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

class AppUser extends Model implements AuthenticatableContract, AuthorizableContract
{
    //use HasRoles, Authorizable;
    use Authorizable;
    
    protected $guard_name = 'web';
    
    protected $username;
    
    protected $email;
    
    protected $id;
    
    public $api_token;
    
    protected $fillable = [
        'id'
    ];
    
    public function __construct($data = null)
    {
        if (!empty($data))
        {
            $this->id = $data['id'];
            $this->email = $data['email'];
            $this->username = $data['name'];
            $this->attributes['id'] = $data['id'];
            $this->api_token = $data['api_token'];
        }
    }
    
    public function fetchUserByCredentials(Array $credentials)
    {   
        $token = $credentials['access_token'];
        $http = new \GuzzleHttp\Client;
        $response = $http->get(config('passport.user'),[
            'headers' => ['Authorization' => 'Bearer ' . $token]
        ]);
        $arr_user = json_decode($response->getBody(), true);
        if (! is_null($arr_user)) {
            $this->username = $arr_user['name'];
            $this->email = $arr_user['email'];
            $this->id = $arr_user['id'];
            $this->api_token = $token;
        }
        return $this;
    }
    
    public function getAuthIdentifierName()
    {
        return $this->username;
    }
    
    public function getAuthIdentifier()
    {
        return $this->id;
    }
    
    public function getAuthPassword()
    {
        //return "";
    }
    
    public function getRememberToken()
    {
        //if (! empty($this->getRememberTokenName())) {
        //    return $this->{$this->getRememberTokenName()};
        //}
    }
    
    public function setRememberToken($value)
    {
        //if (! empty($this->getRememberTokenName())) {
        //    $this->{$this->getRememberTokenName()} = $value;
        //}
    }
    
    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getRememberTokenName()
     */
    public function getRememberTokenName()
    {
        return "";
    }
    
}