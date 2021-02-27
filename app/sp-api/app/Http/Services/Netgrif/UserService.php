<?php


namespace App\Http\Services\Netgrif;


use JsonMapper\JsonMapper;

class UserService extends AbstractNetgrifService
{
    private JsonMapper $mapper;

    public function __construct(JsonMapper $mapper)
    {
        $this->mapper = $mapper;

        $this->apiPaths = [
            'getAllUsingGET2' => 'api/user',
            'getAllAuthoritiesUsingGET' => 'api/user/authority',
            'getLoggedUserUsingGET' => 'api/user/me',
            'preferencesUsingGET' => 'api/user/preferences',
            'savePreferencesUsingPOST' => 'api/user/preferences',
            'getAllWithRoleUsingPOST' => 'api/user/role',
            'searchUsingPOST2' => 'api/user/search',
            'getUserUsingGET' => 'api/user/{id}',
            'updateUserUsingPOST' => 'api/user/{id}',
            'assignAuthorityToUserUsingPOST' => 'api/user/{id}/authority/assign',
            'assignRolesToUserUsingPOST' => 'api/user/{id}/role/assign',
        ];
    }

//    protected static $apiPaths = [
//        'authority' => '/api/user/authority',
//        'me' => '/api/user/me',
//        'preferences' => '/api/user/preferences',
//        'role' => '/api/user/role',
//        'search' => '/api/user/search',
//        'id' => '/api/user/{id}',
//        'assignAuthority' => '/api/user/{id}/authority/assign',
//        'assignRole' => '/api/user/{id}/role/assign',
//    ];
//
//    public static function getMe(bool $small = false): UserResource
//    {
//        $url = self::getFullRequestUrl(self::$apiPaths["me"]);
//
//        $response = self::createAuthRequest()
//            ->get($url, [
//                'small' => $small
//            ]);
//
//        if ($response->failed()) {
//            $response->throw();
//        }
//
//        return $response->json();
//    }
}
