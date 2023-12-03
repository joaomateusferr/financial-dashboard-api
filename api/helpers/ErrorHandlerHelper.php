<?php

class ErrorHandlerHelper {

    public static function handleException(Throwable $Exception): void {

        $Error = ["ErrorCode" => $Exception->getCode(), "ErrorMessage" => $Exception->getMessage(), "ErrorFile" => $Exception->getFile(), "ErrorLine" => $Exception->getLine()];

        if(!empty($GLOBALS['Debug'])){
            $Response = $Error;
        } else{
            $Response = ["ErrorMessage" => "Internal Server Error"];
            error_log(json_encode($Error));
        }

        RequestHelper::prepareResponse(500, $Response);

    }

    public static function handleError(int $ErrorCode, string $ErrorMessage, string $ErrorFile, int $ErrorLine) : bool {
        throw new ErrorException($ErrorMessage, 0, $ErrorCode, $ErrorFile, $ErrorLine);
    }
}