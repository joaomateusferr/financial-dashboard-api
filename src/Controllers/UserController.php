<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\ApiBase;
use App\Repositories\UserRepository;
use App\Services\Password;

class UserController extends ApiBase {

    public function create(Request $Request, Response $Response) {

        $Data = self::formatBody($Request->getBody()->getContents());

        if(!isset($Data['Email']))
            return self::buildResponse($Response, ['Email field is mandatory!'], 400, true);

        if(!filter_var($Data['Email'], FILTER_VALIDATE_EMAIL))
            return self::buildResponse($Response, ['Invalid email!'], 400, true);

        if(!isset($Data['Password']))
            return self::buildResponse($Response, ['Password field is mandatory!'], 400, true);

        $UserDetails = UserRepository::retrieveUserDetailsByEmail($Data['Email']);

        if(is_null($UserDetails))
            return self::buildResponse($Response, ['Unable to fetch data!'], 500, true);

        if(!empty($UserDetails))
            return self::buildResponse($Response, ['User already registered!'], 409, true);

        $PasswordMinimumPasswordSecurityResult = Password::validateMinimumPasswordSecurity($Data['Password']);

        if(!empty($PasswordMinimumPasswordSecurityResult))
            return self::buildResponse($Response, $PasswordMinimumPasswordSecurityResult, 400, true);

        $Result = UserRepository::create($Data['Email'], $Data['Password']);

        if(empty($Result))
            return self::buildResponse($Response, ['Unable to create user!'], 500, true);

        return self::buildResponse($Response, ['User created successfully!']);



    }

}