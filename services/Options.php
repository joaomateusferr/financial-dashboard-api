<?php

class Options {

    public $ServersList;
    public $Credentials;
    public $SSLCertificatePath;

    function __construct() {

        $OptionsPath = Constants::getProjectPath().'/../financial-dashboard-options.json';
        $OptionsJson = file_get_contents($OptionsPath);

        if(!$OptionsJson)
            throw new Exception('Unable to read operations file!');

        $Options = json_decode($OptionsJson, true);

        if(json_last_error() !== JSON_ERROR_NONE)
            throw new Exception('Invalid json!');

        foreach ($Options as $Key => $Option){

            if(property_exists($this, $Key))
                $this->$Key = $Option;

        }

    }

}