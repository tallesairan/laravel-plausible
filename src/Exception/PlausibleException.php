<?php

namespace Airan\Plausible\Exception;



class PlausibleException extends \RuntimeException implements \Throwable
{
    public function __construct(string $message, $data = false)
    {
        if($data){
            $data = var_export($data,true);
        }
        parent::__construct($message.' -> '.$data);

    }

}
