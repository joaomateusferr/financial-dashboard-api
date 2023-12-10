<?php

$Routes = [
    'GET' => [
        'CommonExchangeTradedAssets' => ['Arguments' => [], 'Midwares' => [['Class' => 'AuthenticationMidware', 'Method' => 'validateApiToken']],'Controller' => 'CommonExchangeTradedAssetsController', 'Method' => 'getCommonExchangeTradedAssets'],
        'Positions' => ['Arguments' => ['CustomerID'],'Midwares' => [['Class' => 'AuthenticationMidware', 'Method' => 'validateCustomerToken', 'RequireRequest' => true]], 'Controller' => 'ConsolidatedExchangeTradedAssetsController', 'Method' => 'getPositions', 'RequireRequest' => true]
    ],
    'POST' => [
        'rota/ARG/aaa', 'controler', 'method', 'midwere'
    ],
];