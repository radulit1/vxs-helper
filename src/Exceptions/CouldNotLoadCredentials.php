<?php


namespace VxsBill\Exceptions;


class CouldNotLoadCredentials extends \Exception
{
    public static function passwordNotSet() : self {
        return new static("VXS password not set");
    }

    public static function usernameNotSet() : self {
        return new static("VXS username not set");
    }

    public static function siteNotSet() : self {
        return new static("VXS site not set");
    }

    public static function packageNotSet() : self {
        return new static("VXS package not set");
    }


}

#
