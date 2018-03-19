<?php

namespace Railken\Laravel\ApiHelpers\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Illuminate\Database\Query\Builder;
use Railken\SQ\Contracts\NodeContract;
use Illuminate\Support\Facades\DB;

abstract class BaseOperatorVisitor extends BaseVisitor
{	
	/**
	 * The node that will trigger the visitor
	 *
	 * @var string
	 */
	protected $node = Nodes\Node::class;

	/**
	 * The string operator for the query
	 *
	 * @var string
	 */
	protected $operator = '';

	/**
	 * Visit the node and update the query.
	 *
	 * @param \Illuminate\Database\Query\Builder $builder
	 * @param \Railken\SQ\Contracts\NodeContract $node
	 * @param string $context
	 */
	public function visit($query, $node, string $context)
	{	

        if ($node instanceof $this->node) {

	        $child0 = $node->getChildByIndex(0);
	        $child1 = $node->getChildByIndex(1);

	        if ($child0 instanceof Nodes\KeyNode && $child1 instanceof Nodes\KeyNode) {
		        $context === Nodes\OrNode::class && $query->orWhereColumn($this->parseKey($child0->getValue()), $this->operator, $this->parseKey($child1->getValue()));
		        $context === Nodes\AndNode::class && $query->whereColumn($this->parseKey($child0->getValue()), $this->operator, $this->parseKey($child1->getValue()));
	        }

	        if ($child0 instanceof Nodes\ValueNode && $child1 instanceof Nodes\KeyNode) {
		        $tmp = $child1;
		        $child1 = $child0;
		        $child0 = $tmp;
	        }

	        if ($child0 instanceof Nodes\KeyNode && $child1 instanceof Nodes\ValueNode) {
		        $context === Nodes\OrNode::class && $query->orWhere($this->parseKey($child0->getValue()), $this->operator, $this->parseValue($child1->getValue()));
		        $context === Nodes\AndNode::class && $query->where($this->parseKey($child0->getValue()), $this->operator, $this->parseValue($child1->getValue()));
	        }
    	}
	}

	/**
	 * Parse key.
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function parseKey($key)
	{
		return DB::raw('`'.$key.'`');
	}

	/**
	 * Parse value.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function parseValue($value)
	{
		return $value;
	}
}