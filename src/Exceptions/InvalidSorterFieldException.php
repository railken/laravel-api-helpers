<?php

namespace Railken\Laravel\ApiHelpers\Exceptions;

use Exception;

class InvalidSorterFieldException extends Exception
{
    public function __construct($field)
    {
        $this->message = sprintf("Invalid field '%s'", $field);

        parent::__construct();
    }
}
