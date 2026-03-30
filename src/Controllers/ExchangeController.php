<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\ApiBase;
use App\Repositories\ExchangeRepository;

class ExchangeController extends ApiBase {

    public function get(Request $Request, Response $Response) {

        $ExchangeAlias = self::formatBody($Request->getBody()->getContents());

        if(empty($ExchangeAlias))
            return self::buildResponse($Response, ['Exchange alias are required!'], 400, true);

        $AssetTypeDetails = ExchangeRepository::getExchanges($ExchangeAlias);

        return self::buildResponse($Response, $AssetTypeDetails);

    }

}