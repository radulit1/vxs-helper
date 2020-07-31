<?php

namespace VxsBill\Exceptions;


class InvalidOrderResponse extends \Exception
{
    public static function unsuccessfulResponse($response) : self {
        return new static("VXS unsuccessful response. {$response}");
    }
}
