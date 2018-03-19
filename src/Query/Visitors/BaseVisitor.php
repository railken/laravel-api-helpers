<?php

namespace Railken\Laravel\ApiHelpers\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Railken\Laravel\ApiHelpers\Query\Builder;

class BaseVisitor
{

    /**
     * @var \Railken\Laravel\ApiHelpers\Query\Builder
     */
    protected $builder;

    /**
     * Construct.
     *
     * @param \Railken\Laravel\ApiHelpers\Query\Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Get builder.
     *
     * @return \Railken\Laravel\ApiHelpers\Query\Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }
}
