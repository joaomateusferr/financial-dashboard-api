<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController {

    private const DefaultContentType = 'application/json';

    private static function getDefaultContentType() : string {
        return self::DefaultContentType;
    }

    protected static function formatBody(string $Body) : array {

        if(empty($Body))
            return [];

        if(!json_validate($Body))
            return [];

        return json_decode($Body, true);

    }

    private static function formatResponse(array $Payload, bool $Error = false) : string {

        $Response = [];

        if($Error)
            $Response['error'] = true;

        $Response['result'] = $Payload;

        return json_encode($Response);

    }

    protected static function BuildResponse(Response $Response, array $Data, int $Status = 200, bool $IsError = false) : Response {

        $Response->getBody()->write(self::formatResponse($Data, $IsError));
        return $Response->withHeader('Content-Type', self::getDefaultContentType())->withStatus($Status);

    }

}