<?php

namespace App\UiControllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UiBase;
use App\Services\Password;

class OperationController extends UiBase {

    public function signin(Request $Request, Response $Response) {

        $Data = empty($_POST) ? [] : $_POST;

        $DefaultErrorTitle = 'Error!';
        $DefaultErrorFooter = 'Do you want to try again? <a href="/signin">Signin</a>';

        if(empty($Data['Email']))
            return self::buildResponse($Response, 'signin-result.php', ['Title' => $DefaultErrorTitle, 'Description' => 'The email field is mandatory!', 'Footer' => $DefaultErrorFooter]);

        $Data['Email'] = trim($Data['Email']);

        if(!filter_var($Data['Email'], FILTER_VALIDATE_EMAIL))
            return self::buildResponse($Response, 'signin-result.php', ['Title' => $DefaultErrorTitle, 'Description' => 'The email field must contain a valid email address!', 'Footer' => $DefaultErrorFooter]);

        if(empty($Data['EmailConfirmation']))
            return self::buildResponse($Response, 'signin-result.php', ['Title' => $DefaultErrorTitle, 'Description' => 'The email confirmation field is mandatory!', 'Footer' => $DefaultErrorFooter]);

        $Data['EmailConfirmation'] = trim($Data['EmailConfirmation']);

        if($Data['Email'] != $Data['EmailConfirmation'])
            return self::buildResponse($Response, 'signin-result.php', ['Title' => $DefaultErrorTitle, 'Description' => "The emails don't match!", 'Footer' => $DefaultErrorFooter]);

        if(empty($Data['Password']))
            return self::buildResponse($Response, 'signin-result.php', ['Title' => $DefaultErrorTitle, 'Description' => 'The password field is mandatory!', 'Footer' => $DefaultErrorFooter]);

        $PasswordMinimumPasswordSecurityResult = Password::validateMinimumPasswordSecurity($Data['Password']);

        if(!empty($PasswordMinimumPasswordSecurityResult))
            return self::buildResponse($Response, 'signin-result.php', ['Title' => $DefaultErrorTitle, 'Description' => $PasswordMinimumPasswordSecurityResult[0], 'Footer' => $DefaultErrorFooter]);

        if(empty($Data['PasswordConfirmation']))
            return self::buildResponse($Response, 'signin-result.php', ['Title' => $DefaultErrorTitle, 'Description' => 'The password confirmation field is mandatory!', 'Footer' => $DefaultErrorFooter]);

        if($Data['Password'] != $Data['PasswordConfirmation'])
            return self::buildResponse($Response, 'signin-result.php', ['Title' => $DefaultErrorTitle, 'Description' => "The passwords don't match!", 'Footer' => $DefaultErrorFooter]);

        if(empty($Data['Terms']))
            return self::buildResponse($Response, 'signin-result.php', ['Title' => $DefaultErrorTitle, 'Description' => 'Accepting the terms is mandatory!', 'Footer' => $DefaultErrorFooter]);

        //parametrizar
        $Options = [ 'http' => ['ignore_errors' => true, 'timeout' => 5, 'user_agent' => $_SERVER['HTTP_USER_AGENT'],'header'  => "Content-type: application/json",'method'  => 'POST', 'content' => json_encode(['Email' => $Data['Email'], 'Password' => $Data['Password']])]];
        $Result = @file_get_contents('http://localhost:9999/api/user', false, stream_context_create($Options));

        if(!empty($Result))
            $Result = json_decode($Result, true);

        $Title = !empty($Result['error'])  ? 'Error:' : 'Success:';
        $Description = !empty($Result['result']) ? $Result['result'][0] : '';
        $Footer = !empty($Result['error'])  ? $DefaultErrorFooter : 'Have you activated your account yet? <a href="/login">Login</a>';

        return self::buildResponse($Response, 'signin-result.php', ['Title' => $Title, 'Description' => $Description, 'Footer' => $Footer]);

    }

}