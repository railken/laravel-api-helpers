<?php

namespace Railken\Laravel\ApiHelpers\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class LogicOperatorVisitor
{

	public function __construct($callback)
	{
		$this->_callback = $callback;
	}

	public function callback($query, $node)
	{
		$c = $this->_callback;
		return $c($query, $node);
	}

	public function visit($query, $node)
	{	
        if ($node instanceof Nodes\AndNode) {
			foreach ($node->getChilds() as $child) {
				$query->where(function($q) {
					$this->callback($q, $child);
				});
			}
    	}
	}
}