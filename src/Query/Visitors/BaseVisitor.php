<?php

namespace Railken\Laravel\ApiHelpers\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Illuminate\Database\Query\Builder;
use Railken\SQ\Contracts\NodeContract;

class BaseVisitor
{	

	public function __construct($builder)
	{
		$this->builder = $builder;
	}

	public function getBuilder()
	{
		return $this->builder;
	}

	public function parseKey($key)
	{
		return $key;
	}

	public function parseValue($value)
	{
		return $value;
	}
}