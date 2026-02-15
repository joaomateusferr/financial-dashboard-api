<?php

namespace App\Constants;

class Investidor10Constants {

    private const BaseUrl = 'https://investidor10.com.br';

    private const AsetTypeMap = [
        'FII' => 'fiis',
        'ACAO' => 'acoes',
        'ETF-BR' => 'etfs',
        'BDR' => 'bdrs',
        'STOCK' => 'stocks',
        'ETF-US' => 'etfs-global',
        'ADR' => 'stocks',
        'REIT' => 'reits',
    ];

    private const DividendTableFieldMap = [
        'tipo' => 'Type',
        'pagamento' => 'PaymentDate',
        'valor' => 'Income',
        'data com' => 'ExDate',
    ];

    public static function getBaseUrl() : string {
        return self::BaseUrl;
    }

    public static function getDividendTableFieldMap() : array {
        return self::DividendTableFieldMap;
    }

    public static function getAsetTypeMap() : array {
        return self::AsetTypeMap;
    }

}