<?php

namespace Railken\Laravel\ApiHelpers\Exceptions;

use Exception;

class FilterUndefinedKeyException extends Exception
{
	public function __construct($key)
	{
		$this->message = sprintf("Undefined '%s' key", $key);

		parent::__construct();
	}
}