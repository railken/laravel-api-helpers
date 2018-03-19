<?php

namespace Railken\Laravel\ApiHelpers\Exceptions;

use Exception;

class InvalidSorterDirectionException extends Exception
{
    public function __construct($direction)
    {
        $this->message = sprintf("Invalid value '%s', expected: 'asc', 'desc'", $direction);
    }
}
