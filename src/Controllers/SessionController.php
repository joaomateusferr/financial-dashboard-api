<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\UserRepository;
use App\Services\Password;
use App\Repositories\SessionRepository;

class SessionController extends BaseController {

    public function set(Request $Request, Response $Response) : Response {

        $Data = self::formatBody($Request->getBody()->getContents());

        if(!isset($Data['Email']))
            return self::buildResponse($Response, ['Email field is mandatory!'], 400, true);

        if(!filter_var($Data['Email'], FILTER_VALIDATE_EMAIL))
            return self::buildResponse($Response, ['Invalid email!'], 400, true);

        if(!isset($UserData['Password']))
            return self::buildResponse($Response, ['Password field is mandatory!'], 400, true);

        $UserDetails = UserRepository::retrieveUserDetailsByEmail($Data['Email']);

        if(is_null($UserDetails))
            return self::buildResponse($Response, ['Unable to fetch user data!'], 500, true);

        if(empty($UserDetails))
            return self::buildResponse($Response, ['Invalid user!'], 401, true);

        if(!Password::verifyPasswordHash($Data['Password'], $UserDetails['PasswordHash']))
            return self::buildResponse($Response, ['Invalid password!'], 401, true);

        $Session = SessionRepository::set($UserDetails['ID'], $_SERVER['HTTP_USER_AGENT']);

        if(empty($Session))
            return self::buildResponse($Response, ['Invalid session!'], 401, true);

       setcookie('sid', $Session['SID'], $Session['ExpiresAt'], '/', '', true);

       return self::buildResponse($Response, ['Logged in successfully!']);

    }
}