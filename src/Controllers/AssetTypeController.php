<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\ApiBase;
use App\Repositories\AssetTypeRepository;

class AssetTypeController extends ApiBase {

    public function get(Request $Request, Response $Response) {

        $AssetTypes = self::formatBody($Request->getBody()->getContents());

        if(empty($AssetTypes))
            return self::buildResponse($Response, ['Asset types are required!'], 400, true);

        $AssetTypeDetails = AssetTypeRepository::getAssetTypes($AssetTypes);

        return self::buildResponse($Response, $AssetTypeDetails);

    }

}