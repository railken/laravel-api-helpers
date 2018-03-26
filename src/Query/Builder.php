<?php

namespace Railken\Laravel\ApiHelpers\Query;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class Builder
{
    /**
     * @var string
     */
    protected $context;

    /**
     * @var array
     */
    protected $visitors;

    /**
     * @var array
     */
    protected $keys;

    /**
     * Construct.
     *
     * @var array
     */
    public function __construct($keys)
    {
        $this->context = Nodes\AndNode::class;
        $this->keys = $keys;

    }

    /**
     * Set context.
     *
     * @param string $context
     *
     * @return $this
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Set visitors.
     *
     * @param array $visitors
     *
     * @return $this
     */
    public function setVisitors($visitors)
    {
        $this->visitors = $visitors;

        return $this;
    }

    /**
     * Get context.
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Build the query.
     *
     * @param mixed $query
     * @param \Railken\SQ\Contracts\NodeContract $node
     * @param string                             $context
     *
     * @return void
     */
    public function build($query, NodeContract $node, $context = Nodes\AndNode::class)
    {
        foreach ($this->visitors as $visitor) {
            $visitor->visit($query, $node, $context);
        }
    }
}
