<?php

namespace Railken\Laravel\ApiHelpers\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class LteVisitor extends BaseOperatorVisitor
{
    /**
     * The node that will trigger the visitor
     *
     * @var string
     */
    protected $node = Nodes\LteNode::class;

    /**
     * The string operator for the query
     *
     * @var string
     */
    protected $operator = '<=';
}
