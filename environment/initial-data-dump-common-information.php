<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\MariaDB;

try{

    $Connection = new MariaDB('common-information', 'common_information');

    $Queries = [
        "INSERT INTO currencys (IsoCode, Name, Symbol) VALUES
        ('USD', 'Dollar', '$'),
        ('BTC', 'Bitcoin', NULL),
        ('BRL', 'Real', 'R$')",
        "INSERT INTO asset_qualifications (ID, Name) VALUES
        (1, 'Direito de Subscrição – Ação Ordinária (ON)'),
        (2, 'Direito de Subscrição – Ação Preferencial (PN)'),
        (3, 'Ação Ordinárias (ON)'),
        (4, 'Ação Preferencial (PN)'),
        (5, 'Ação Preferencial Classe A (PNA)'),
        (6, 'Ação Preferencial Classe B (PNB)'),
        (7, 'Ação Preferencial Classe C (PNC)'),
        (8, 'Ação Preferencial Classe D (PND)'),
        (9, 'Recibo de Subscrição – Ação Ordinária (ON)'),
        (10, 'Recibo de Subscrição – Ação Preferencial(PN)'),
        (11, 'ETF, Unit ou FII'),
        (12, 'Direito de Subscrição - ETF, Unit ou FII'),
        (13, 'Recibo de Subscrição - ETF, Unit ou FII'),
        (14, 'Recibo de Subscrição (Sobras) - ETF, Unit ou FII'),
        (18, 'Unknown'),
        (32, 'Brazilian Depositary Receipts Patrocinado Nível II'),
        (33, 'Brazilian Depositary Receipts Patrocinado Nível III'),
        (34, 'Brazilian Depositary Receipts Não Patrocinado'),
        (35, 'Brazilian Depositary Receipts Não Patrocinado')",
        "INSERT INTO asset_types (Name, Identifier) VALUES
        ('Exchange Traded Fund - United States', 'ETF-US'),
        ('Stock', 'STOCK'),
        ('Exchange Traded Fund - Brasil', 'ETF-BR'),
        ('Ação', 'ACAO'),
        ('Cryptocurrency', 'CRYPTO'),
        ('Renda Fixa - Brasil', 'RF'),
        ('Fixed Income - United States', 'FI'),
        ('Real Estate Investment Trusts', 'REIT'),
        ('American Depositary Receipt', 'ADR'),
        ('Brazilian Depositary Receipts', 'BDR'),
        ('Fundo de Investimento Imobiliário', 'FII')",
        "INSERT INTO exchanges (YFinanceAlias, Name, Alias) VALUES
        ('SA', 'Brasil, Bolsa e Balcão', 'B3')"
    ];

    foreach($Queries as $Sql){

        $Stmt = $Connection->prepare($Sql);
        $Result = $Stmt->execute();

    }

} catch (Exception $Exception) {

    echo $Exception->getMessage()."\n";

} finally {

    $Connection->close();

}