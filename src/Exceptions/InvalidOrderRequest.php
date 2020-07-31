<?php


namespace VxsBill\Exceptions;


class InvalidOrderRequest extends \Exception
{
    public static function unsuccessfulResponse(Response $response) : self {
        return new static("VXS unsuccessful response. {$response->body()}");
    }
}
