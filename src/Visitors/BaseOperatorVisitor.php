<?php

namespace Railken\Laravel\ApiHelpers\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Illuminate\Database\Query\Builder;
use Railken\SQ\Contracts\NodeContract;


class BaseOperatorVisitor
{

	public function __construct($context)
	{
		$this->context = $context;
	}

	/**
	 * Visit the node and update the query
	 *
	 * @param \Illuminate\Database\Query\Builder $builder
	 * @param \Railken\SQ\Contracts\NodeContract $node
	 */
	public function visit($query, $node)
	{	

        if ($node instanceof $this->node) {
			$bindings = [];
	        $sql = [];

	        if ($node->getChildByIndex(0) instanceof Nodes\KeyNode) {
	            $sql[] = $node->getChildByIndex(0)->getValue();
	        }

	        if ($node->getChildByIndex(0) instanceof Nodes\ValueNode) {
	            $bindings['p0'] = $node->getChildByIndex(0)->getValue();
	            $sql[] = ':p0';
	        }


	       	$sql[] = $this->operator;


	        if ($node->getChildByIndex(1) instanceof Nodes\KeyNode) {
	            $sql[] = $node->getChildByIndex(1)->getValue();
	        }

	        if ($node->getChildByIndex(1) instanceof Nodes\ValueNode) {
	            $bindings['p1'] = $node->getChildByIndex(1)->getValue();
	            $sql[] = ':p1';
	        }
	        $this->context == Nodes\OrNode::class && $query->orWhereRaw(implode(" ", $sql), $bindings);
	        $this->context == Nodes\AndNode::class && $query->whereRaw(implode(" ", $sql), $bindings);

    	}
	}
}