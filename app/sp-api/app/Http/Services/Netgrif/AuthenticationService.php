<?php


namespace App\Http\Services\Netgrif;


use App\Models\Netgrif\UserResource;
use App\Models\User\LoginCredentials;
use Illuminate\Support\Facades\Http;
use JsonMapper\JsonMapper;


class AuthenticationService extends AbstractNetgrifService
{
    private JsonMapper $mapper;

    public function __construct(JsonMapper $mapper)
    {
        $this->mapper = $mapper;

        $this->apiPaths = [
            'changePasswordUsingPOST' => 'api/auth/changePassword',
            'inviteUsingPOST' => 'api/auth/invite',
            'loginUsingGET' => 'api/auth/login',
            'recoverAccountUsingPOST' => 'api/auth/recover',
            'resetPasswordUsingPOST' => 'api/auth/reset',
            'signupUsingPOST' => 'api/auth/signup',
            'verifyTokenUsingPOST' => 'api/auth/token/verify',
            'verifyAuthTokenUsingGET' => 'api/auth/verify',
        ];
    }


    public function login(LoginCredentials $loginCredentials): UserResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['loginUsingGET']);

        $response = Http::withBasicAuth(
            $loginCredentials->email,
            $loginCredentials->password
        )->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        $user = new UserResource();
        $this->mapper->mapObject($response->object(), $user);
        return $user;
    }
}
