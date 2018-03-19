<?php

namespace Railken\Laravel\ApiHelpers\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Illuminate\Database\Query\Builder;
use Railken\SQ\Contracts\NodeContract;

class NotNullVisitor extends BaseOperatorVisitor
{
    /**
     * The node that will trigger the visitor
     *
     * @var string
     */
    protected $node = Nodes\NotNullNode::class;

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
            $bindings = [];
            $sql = [];

            $child0 = $node->getChildByIndex(0);
            $child1 = $node->getChildByIndex(1);

            if ($child0 instanceof Nodes\KeyNode) {
                $context === Nodes\OrNode::class && $query->orWhereNotNull($this->parseKey($child0->getValue()));
                $context === Nodes\AndNode::class && $query->whereNotNull($this->parseKey($child0->getValue()));
            }
        }
    }
}
