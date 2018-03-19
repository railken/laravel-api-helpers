<?php

namespace Railken\Laravel\ApiHelpers\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Illuminate\Database\Query\Builder;
use Railken\SQ\Contracts\NodeContract;

class AndVisitor extends LogicOperatorVisitor
{


	/**
	 * Visit the node and update the query
	 *
	 * @param \Illuminate\Database\Query\Builder $builder
	 * @param \Railken\SQ\Contracts\NodeContract $node
	 */
	public function visit(Builder $query, NodeContract $node)
	{	

        if ($node instanceof Nodes\AndNode) {

        	$callback = function($q) use ($node) {
				foreach ($node->getChilds() as $child) {
					print_r($child->toArray());
					$this->getBuilder()->setContext(Nodes\AndNode::class);
					$this->getBuilder()->build($q, $child);
				}
			};
			
        	$this->getBuilder()->getContext() == Nodes\OrNode::class && $query->orWhere($callback);
        	$this->getBuilder()->getContext() == Nodes\AndNode::class && $query->where($callback);

    	}
	}
}