<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\RequestHelper;
use App\Helpers\ResponseHelper;
use App\Repositories\UserRepository;
use \Exception;

class UserController {

    public function create(Request $Request, Response $Response) {

        $Data = RequestHelper::formatBody($Request->getBody()->getContents());

        $ResultCode = 0;

        try{

            $UserResult = UserRepository::create($Data);

            $ResultCode = $UserResult['Code'];
            $Response->getBody()->write(ResponseHelper::format(['message' => $UserResult['Message']]));


        } catch (Exception $Exception){

            $ResultCode = 400;
            $Response->getBody()->write(ResponseHelper::format(['message' => $Exception->getMessage()]));

        }

        return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType())->withStatus($ResultCode);

    }

    public function login(Request $Request, Response $Response) {

        $Data = RequestHelper::formatBody($Request->getBody()->getContents());

        $ResultCode = 0;

        try{

            $UserResult = UserRepository::login($Data);

            $ResultCode = $UserResult['Code'];
            $Response->getBody()->write(ResponseHelper::format(['message' => $UserResult['Message']]));


        } catch (Exception $Exception){

            $ResultCode = 400;
            $Response->getBody()->write(ResponseHelper::format(['message' => $Exception->getMessage()]));

        }

        return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType())->withStatus($ResultCode);

    }

}