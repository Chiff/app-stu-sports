<?php


namespace App\Http\Services\Netgrif;


use App\Models\Netgrif\EmbeddedUsers;
use App\Models\Netgrif\UserResource;
use JsonMapper\JsonMapper;
use phpDocumentor\Reflection\Types\Iterable_;

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

    public function getLoggedUserUsingGET(): UserResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['getLoggedUserUsingGET']);
        $response = self::beginRequestAsSystem()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        $user = new UserResource();
        $this->mapper->mapObject($response->object(), $user);

        return $user;
    }

    public function getAllUsingGET(): EmbeddedUsers
    {
        $url = self::getFullRequestUrl($this->apiPaths['getAllUsingGET2']);
        $response = self::beginRequestAsSystem()->get($url);

        if ($response->failed()) {
            $response->throw();
        }
        $users = new EmbeddedUsers();
        $this->mapper->mapObject($response->object(), $users);
        return $users;
    }

    public function searchUsingPOST(string $fulltext, string $roles): EmbeddedUsers
    {
        $url = self::getFullRequestUrl($this->apiPaths['getAllUsingGET2']);
        $response = self::beginRequestAsSystem()->get($url, array('fulltext' => $fulltext, 'roles' => $roles));

        if ($response->failed()) {
            $response->throw();
        }
        $users = new EmbeddedUsers();
        $this->mapper->mapObject($response->object(), $users);
        return $users;
    }


    public function updateUserUsingPOST($id, string $avatar = null, string $name = null, string $surname = null, string $telNumber = null): UserResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['getAllUsingGET2']);
        $response = self::beginRequestAsSystem()->get($url, array('avatar' => $avatar, 'name' => $name, 'surname' => $surname, 'telNumber' => $telNumber));

        if ($response->failed()) {
            $response->throw();
        }
        $users = new UserResource();
        $this->mapper->mapObject($response->object(), $users);
        return $users;
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
