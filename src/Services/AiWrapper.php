<?php

namespace App\Services;

abstract class AiWrapper {

    protected bool $Store = false;
    protected ?float $Temperature = null;
    protected string $Model;
    protected string $ApiToken;
    protected string $Content;


    public function __construct(string $ApiToken, string $Model, ?float $Temperature = null){

        $this->ApiToken = $ApiToken;
        $this->Model = $Model;

        if(!is_null($Temperature))
            $this->Temperature = $Temperature;

    }

    protected function setContent(string $Content) : void {

        $this->Content = $Content;

    }

    abstract protected function buildReasoning() : string;

    protected function buildPromptInput() : array {

        return
        [
            [
                "role" => "system",
                "content" => [
                    [
                        "type" => "input_text",
                        "text" => $this->buildReasoning()
                    ]
                ]
            ],
            [
                "role" => "user",
                "content" => [
                    [
                        "type" => "input_text",
                        "text" => $this->Content
                    ]
                ]
            ]
        ];

    }

    protected function buildPrompt() : array {

        $Prompt = [
            "model" => $this->Model,
            "store" => $this->Store,
            "input" => $this->buildPromptInput(),
        ];

        if(!is_null($this->Temperature))
            $Prompt['temperature'] = $this->Temperature;

        return $Prompt;

    }

    protected function sendRequest(array $Prompt) : ?array {

        $Curl = curl_init();
        $Url = "https://api.openai.com/v1/responses";
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json","Authorization: Bearer ".$this->ApiToken]);
        curl_setopt($Curl, CURLOPT_POST, true);
        curl_setopt($Curl, CURLOPT_POSTFIELDS, json_encode($Prompt));
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        $Response = curl_exec($Curl);
        $HttpCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);

        if(empty($Response))
            return null;

        if($HttpCode != 200){

            error_log($Response);
            return null;

        }

        if(!json_validate($Response))
            return null;

        if(!empty($GLOBALS["DebugAiApiResponse"]))
            error_log($Response);

        return json_decode($Response, true);

    }

    abstract protected function parseResponse(array $Response) : ?array;

    public function categorize(string $Content) : ?array {

        $this->setContent($Content);
        $Prompt = $this->buildPrompt();
        $Response = $this->sendRequest($Prompt);

        if(is_null($Response))
            return null;

        return $this->parseResponse($Response);

    }

}