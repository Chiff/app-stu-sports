<?php


namespace App\Http\Services\Netgrif;


use App\Models\Netgrif\UserResource;
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


    public function login(): UserResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['loginUsingGET']);
        $response = self::beginRequest()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        $user = new UserResource();
        $this->mapper->mapObject($response->object(), $user);
        return $user;
    }
}
