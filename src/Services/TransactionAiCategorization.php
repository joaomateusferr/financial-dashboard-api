<?php

namespace App\Services;

use App\Services\AiWrapper;
use \Exception;

final class TransactionAiCategorization extends AiWrapper {

    public function __construct(){

        $OpenaiApiKey =  getenv('OPENAI_API_KEY');

        if(empty($OpenaiApiKey))
            throw new Exception("Empty Openai Api Key");

        parent::__construct($OpenaiApiKey,'gpt-4.1-nano',0.2);

    }

    private function getCategories() : array {

        return [
            'Alimentação',
            'Benefícios',
            'Bônus e PRL',
            'Casa','Compras',
            'Contas',
            'Crédito e Financiamento',
            'Cuidados Pessoais',
            'Doações',
            'Educação',
            'Impostos e Tributos',
            'Investimentos',
            'Lazer e Entretenimento',
            'Outra Categoria',
            'Pets',
            'Receita de Aluguel',
            'Reembolso',
            'Salário',
            'Saque',
            'Saúde',
            'Seguro',
            'Supermercado',
            'Tarifas',
            'Transferências',
            'Transporte',
            'Viagem'
        ];

    }

    protected function buildReasoning() : string {

        return 'A partir das categorias: '.implode(',',$this->getCategories()).'. Retornando somente o nome de uma categora na respoosta como devemos clasificaria o gasto com o nome:';

    }

    protected function parseResponse(array $Response) : ?array {

        if(!isset($Response['output'][0]['content'][0]['text']))
            return null;

        return[trim($Response['output'][0]['content'][0]['text'])];

    }

}