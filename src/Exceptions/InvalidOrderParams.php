<?php

namespace VxsBill\Exceptions;

class InvalidOrderParams extends \Exception
{
    public static function priceNotSet() : self {
        return new static("VXS order price not set");
    }
    public static function invalidPriceFormat() : self {
        return new static("VXS order invalid price format");
    }

    public static function descriptionNotSet() : self {
        return new static("VXS description price not set");
    }

    public static function currencyNotSet() : self {
        return new static("VXS currency not set");
    }
}
