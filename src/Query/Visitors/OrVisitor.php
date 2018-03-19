<?php

namespace Railken\Laravel\ApiHelpers\Query\Visitors;

use Illuminate\Database\Query\Builder;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class OrVisitor extends LogicOperatorVisitor
{
    /**
     * Visit the node and update the query.
     *
     * @param \Illuminate\Database\Query\Builder $builder
     * @param \Railken\SQ\Contracts\NodeContract $node
     * @param string                             $context
     */
    public function visit(Builder $query, NodeContract $node, string $context)
    {
        if ($node instanceof Nodes\OrNode) {
            $callback = function ($q) use ($node) {
                foreach ($node->getChilds() as $child) {
                    $this->getBuilder()->build($q, $child, Nodes\OrNode::class);
                }
            };

            $context === Nodes\OrNode::class && $query->orWhere($callback);
            $context === Nodes\AndNode::class && $query->where($callback);
        }
    }
}
