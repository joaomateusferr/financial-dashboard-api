<?php

namespace App\UiControllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UiBase;

class OperationController extends UiBase {

    public function signin(Request $Request, Response $Response) {

        $Data = empty($_POST) ? [] : $_POST;
        $Result = [];

        $Success = true;

        if($Success){

            $Title = 'Account successfully created!';
            $Description = 'Validate your account by accessing your email!';

        } else {

            $Title = 'Error during account creation!';
            $Description = !empty($Result['error']) ? $Result['error'] : '';

        }

        return self::buildResponse($Response, 'signin-result.php', ['Title' => $Title, 'Description' => $Description]);

    }

}