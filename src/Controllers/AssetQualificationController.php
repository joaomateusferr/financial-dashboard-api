<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\ApiBase;
use App\Repositories\AssetQualificationRepository;

class AssetQualificationController extends ApiBase {

    public function get(Request $Request, Response $Response) {

        $AssetQualifications = self::formatBody($Request->getBody()->getContents());

        if(empty($AssetQualifications))
            return self::buildResponse($Response, ['Asset qualifications are required!'], 400, true);

        $AssetQualificationsDetails = AssetQualificationRepository::getAssetQualifications($AssetQualifications);

        return self::buildResponse($Response, $AssetQualificationsDetails);

    }

}