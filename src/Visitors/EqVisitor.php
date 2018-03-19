<?php

namespace Railken\Laravel\ApiHelpers\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class EqVisitor extends BaseOperatorVisitor
{	
	/**
	 * The node that will trigger the visitor
	 *
	 * @var string
	 */
	protected $node = Nodes\EqNode::class;

	/**
	 * The string operator for the query
	 *
	 * @var string
	 */
	protected $operator = '=';
}