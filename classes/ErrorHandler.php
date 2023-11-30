<?php

class ErrorHandler {

    public static function handleException(Throwable $Exception): void {

        $Response = [
            "Code" => $Exception->getCode(),
            "Message" => $Exception->getMessage(),
            "File" => $Exception->getFile(),
            "Line" => $Exception->getLine()
        ];

        Request::prepareResponse(500, $Response);

    }

    public static function handleError(int $ErrorCode, string $ErrorMessage, string $ErrorFile, int $ErrorLine) : bool {
        throw new ErrorException($ErrorMessage, 0, $ErrorCode, $ErrorFile, $ErrorLine);
    }
}