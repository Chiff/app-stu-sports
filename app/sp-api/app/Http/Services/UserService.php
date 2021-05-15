<?php


namespace App\Http\Services;

use App\Dto\User\UserDTO;
use App\Http\Services\AS\SystemAS;
use App\Http\Services\AS\UserTeamAS;
use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Netgrif\EmbeddedUsers;
use App\Models\User;
use App\Models\User\LoginCredentials;
use JsonMapper\JsonMapper;

class UserService
{
    private AuthenticationService $netgrigfAuth;
    private Netgrif\UserService $netgrifUser;
    private JsonMapper $mapper;
    private UserTeamAS $userTeamAS;
    private SystemAS $systemAS;

    public function __construct(
        AuthenticationService $authService,
        Netgrif\UserService $netgrifUserService,
        UserTeamAS $userTeamAS,
        JsonMapper $mapper,
        SystemAS $systemAS
    )
    {
        $this->netgrigfAuth = $authService;
        $this->netgrifUser = $netgrifUserService;
        $this->userTeamAS = $userTeamAS;

        $this->mapper = $mapper;
        $this->systemAS = $systemAS;
    }

    public function login(LoginCredentials $credentials): User|null
    {
        $userResource = $this->netgrigfAuth->login($credentials);


        if ($userResource == null) {
            return null;
        }

        $user = User::whereExtId($userResource->id)->first();

        // prve prihlasenie ("registracia")
        if (!$user) {
            $user = new User;
            $user->email = $userResource->email;
            $user->firstname = $userResource->name;
            $user->surname = $userResource->surname;
            $user->ext_id = $userResource->id;
            
            if (!$user->save()) {
                return null;
            }
        }

        return $user;
    }

    public function detail(): UserDTO|null
    {
        $user = User::whereId(auth()->id())->get()->first();
        $userDTO = new UserDTO();
        $this->mapper->mapObjectFromString($user->toJson(), $userDTO);
        $userDTO->teams = $this->userTeamAS->getTeamsByUser($user);

        return $userDTO;
    }

    public function getAllUsers(): EmbeddedUsers
    {
        return $this->netgrifUser->getAllUsingGET();
    }


    // https://stackoverflow.com/a/52495210
    private static string $credentialsSeparator = "_:_";
    private static string $cipher = "aes-256-gcm";

    // save credentials as [login]_:_[password] string, encrypted with jwt token
    public function encodeCredentials(LoginCredentials $credentials)
    {
        $user = $this->getLoggedUserAsModel();
        $token = auth()->tokenById($user->getAuthIdentifier());

        $data = $credentials->email . self::$credentialsSeparator . $credentials->password;
        $iv_len = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($iv_len);
        $tag = ""; // will be filled by openssl_encrypt
        $tag_length = 16;

        $cipherText = openssl_encrypt($data, self::$cipher, $token, OPENSSL_RAW_DATA, $iv, $tag, "", $tag_length);
        $encrypted = base64_encode($iv . $cipherText . $tag);

        $user->encrypted_auth = $encrypted;
        $user->save();
    }

    // decrypt credentials to base64 string [login:password] from jwt token
    public function decodeCredentials(): LoginCredentials
    {
        $credentials = new LoginCredentials();

        $user = $this->getLoggedUserAsModel();
        $token = auth()->tokenById($user->getAuthIdentifier());

        $textToDecrypt = $user->encrypted_auth;
        $encrypted = base64_decode($textToDecrypt);
        $iv_len = openssl_cipher_iv_length(self::$cipher);
        $tag_length = 16;
        $iv = substr($encrypted, 0, $iv_len);
        $ciphertext = substr($encrypted, $iv_len, -$tag_length);
        $tag = substr($encrypted, -$tag_length);

        $decrypted = openssl_decrypt($ciphertext, self::$cipher, $token, OPENSSL_RAW_DATA, $iv, $tag);
        $data = preg_split("/" . self::$credentialsSeparator . "/", $decrypted);

        $credentials->email = $data[0];
        $credentials->password = $data[1];

        return $credentials;
    }

    private function getLoggedUserAsModel(): User
    {
        return auth()->user();
    }
}
