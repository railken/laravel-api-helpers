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

        $this->visitors = [
            new Visitors\EqVisitor($this),
            new Visitors\NotEqVisitor($this),
            new Visitors\GtVisitor($this),
            new Visitors\GteVisitor($this),
            new Visitors\LtVisitor($this),
            new Visitors\LteVisitor($this),
            new Visitors\CtVisitor($this),
            new Visitors\SwVisitor($this),
            new Visitors\EwVisitor($this),
            new Visitors\AndVisitor($this),
            new Visitors\OrVisitor($this),
            new Visitors\NotInVisitor($this),
            new Visitors\InVisitor($this),
            new Visitors\NullVisitor($this),
            new Visitors\NotNullVisitor($this),
        ];

        /*if (!in_array($key, $this->keys)) {
            throw new Exceptions\FilterUndefinedKeyException($key);
        }*/
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
