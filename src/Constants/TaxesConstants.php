<?php

namespace App\Constants;

class TaxesConstants {

    private const BrazilianTaxes = [
        'ACAO' => [
            'JSCP' => 0.15,
        ],
        'ETF-BR' => [
            'Dividendos' => 0.15,
        ],
        'ETF-US' => [
            'Dividendos' => 0.3,
        ],
        'REIT' => [
            'Dividendos' => 0.3,
        ],
    ];

    public static function getTaxesMap() : array {
        return self::BrazilianTaxes;
    }

}