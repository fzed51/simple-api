<?php /** @noinspection SqlResolve */


namespace App\action;


use App\ApiSecurity;
use DateTime;
use Slim\Http\Request;

class AuthUser extends UserAccess
{
    /**
     * @param \Slim\Http\Request $request
     * @return string|null
     * @throws \Exception
     */
    public function __invoke(Request $request): ?string
    {
        $headers = $request->getHeaders();
        $token = $this->getBearerToken($headers);
        if ($token === null) {
            return null;
        }
        $sql = "SELECT * FROM user WHERE session_public_token = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$token]);
        $user = $stm->fetch(\PDO::FETCH_ASSOC);
        if ($user === false) {
            return null;
        }
        $security = new ApiSecurity();
        if (new DateTime($user['session_expiration']) < new DateTime()) {
            return null;
        }
        if (!$security->testPublicToken(
            $token,
            $user['session_private_token'],
            $security->getIdClient()
        )) {
            return null;
        }
        return $user['ref'];
    }


    /**
     * Get header Authorization
     * @param array $headers
     * @return string|null
     */
    protected function getAuthorizationHeader(array $headers): ?string
    {
        $headers = array_merge($headers);
        array_change_key_case($headers, CASE_UPPER);
        $hauthHeader = null;
        if (isset($headers['AUTHORIZATION'])) {
            if (is_array($headers['AUTHORIZATION'])) {
                $headers['AUTHORIZATION'] = implode(' ', $headers['AUTHORIZATION']);
            }
            $hauthHeader = trim($headers["AUTHORIZATION"]);
        } elseif (isset($headers['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            if (is_array($headers['HTTP_AUTHORIZATION'])) {
                $headers['HTTP_AUTHORIZATION'] = implode(' ', $headers['HTTP_AUTHORIZATION']);
            }
            $hauthHeader = trim($headers["HTTP_AUTHORIZATION"]);
        }
        // elseif (function_exists('apache_request_headers')) {
        //     $requestHeaders = apache_request_headers();
        //     // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        //     $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //     //print_r($requestHeaders);
        //     if (isset($requestHeaders['Authorization'])) {
        //         $hauthHeader = trim($requestHeaders['Authorization']);
        //     }
        // }
        return $hauthHeader;
    }

    /**
     * get access token from header
     * @return string|null
     */
    protected function getBearerToken(array $headers): ?string
    {
        $hauthHeader = $this->getAuthorizationHeader($headers);
        // HEADER: Get the access token from the header
        if (!empty($hauthHeader)) {
            if (preg_match('/Bearer\s(\S+)/', $hauthHeader, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}