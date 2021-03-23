<?php


namespace Core;

use Exception;

class CustomException extends Exception
{
    public function errorLog() : void {
        print json_encode([
            'error' => [
                'message' => $this->getMessage()
            ]
        ]);
        die();
    }
}