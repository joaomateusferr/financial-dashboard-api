<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\ApiBase;
use App\Repositories\SessionRepository;
use App\Repositories\ExchangeRepository;
use App\Repositories\ExchangeTradedAssetsRepository;

class ExchangeTradedAssetsController extends ApiBase {

    public function create(Request $Request, Response $Response) {

        $IsAdmin = SessionRepository::isAdmin($_COOKIE["sid"]);

        if(is_null($IsAdmin))
            return self::buildResponse($Response, ['Unable to fetch Session data!'], 500, true);

        if($IsAdmin === false)
            return self::buildResponse($Response, ['Unauthorized!'], 401, true);

        $Assets = self::formatBody($Request->getBody()->getContents());

        if(count($Assets) > 25)
            return self::buildResponse($Response, ['Asset limit per request is 25!'], 400, true);

        $ExchangeIDs = [];

        foreach($Assets as $Index => $Asset){

            if(!isset($Asset['Ticker']))
                return self::buildResponse($Response, ["I - $Index - Ticker is required!"], 400, true);

            if(!isset($Asset['AssetTypeID']))
                return self::buildResponse($Response, ["I - $Index - AssetTypeID is required!"], 400, true);

            if(!isset($Asset['IsoCode']))
                return self::buildResponse($Response, ["I - $Index - IsoCode is required!"], 400, true);

            if($Asset['IsoCode'] != 'USD' && !isset($Asset['ExchangeID']))
                return self::buildResponse($Response, ["I - $Index - ExchangeID is required for assets not denominated in dollars!"], 400, true);

            if($Asset['IsoCode'] == 'BRL' && !isset($Asset['AssetQualificationID']))
                return self::buildResponse($Response, ["I - $Index - AssetQualificationID is required for assets not denominated in reais!"], 400, true);

            if(isset($Asset['ExchangeID']) && !isset($ExchangeIDs[$Asset['ExchangeID']]))
                $ExchangeIDs[$Asset['ExchangeID']] = true;

            if(!isset($Asset['AssetQualificationID']))
                $Assets[$Index]['AssetQualificationID'] = null;

            if(!isset($Asset['ExchangeID']))
                $Assets[$Index]['ExchangeID'] = null;

            $Assets[$Index]['UpdateDate'] = null;

            if(!isset($Asset['AssetSubtypeID']))
                $Assets[$Index]['AssetSubtypeID'] = null;

        }

        if(!empty($ExchangeIDs)){

            $ExchangeIDs = array_keys($ExchangeIDs);
            $ExchangesByID = ExchangeRepository::getExchangesByID($ExchangeIDs, ['Name']);

            foreach($ExchangeIDs as $ID){

                if(!isset($ExchangesByID[$ID]))
                    return self::buildResponse($Response, ["Invalid exchange ID ($ID)!"], 400, true);

            }

        }

        $AssetsToUpdate = ExchangeTradedAssetsRepository::insert($Assets);
        $UpdateResult = ExchangeTradedAssetsRepository::update($AssetsToUpdate);

        return self::buildResponse($Response, $UpdateResult);



    }

}