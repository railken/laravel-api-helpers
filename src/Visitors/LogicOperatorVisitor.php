<?php

namespace Railken\Laravel\ApiHelpers\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Illuminate\Database\Query\Builder;
use Railken\SQ\Contracts\NodeContract;

class LogicOperatorVisitor
{

	public function __construct($context, $callback)
	{
		$this->context = $context;
		$this->_callback = $callback;
	}

	public function callback($query, $node)
	{
		$c = $this->_callback;
		return $c($query, $node);
	}
}