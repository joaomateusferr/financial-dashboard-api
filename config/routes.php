<?php

$Routes = [
    'GET' => [
        'CommonExchangeTradedAssets' => [
            'Arguments' => [] ,
            'Midwares' => [
                [
                    'Class' => 'AuthenticationMidware',
                    'Method' => 'validateApiToken'
                ]
            ],
            'Controller' => 'CommonExchangeTradedAssetsController',
            'Method' => 'getCommonExchangeTradedAssets'
        ]
    ],
    'POST' => [
        'rota/ARG/aaa', 'controler', 'method', 'midwere'
    ],
];