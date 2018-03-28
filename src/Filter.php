<?php

namespace Railken\Laravel\ApiHelpers;

use Railken\SQ\Languages\BoomTree\Resolvers as Resolvers;
use Railken\SQ\QueryParser;
use Railken\Laravel\ApiHelpers\Query\Visitors as Visitors;

class Filter
{
    /**
     * @var array
     */
    protected $keys;

    /**
     * Set keys.
     *
     * @param array $keys
     *
     * @return $this
     */
    public function setKeys($keys)
    {
        $this->keys = $keys;

        return $this;
    }

    /**
     * Get keys.
     *
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Filter query with where.
     *
     * @return Query\Builder
     */
    public function getBuilder()
    {
        $builder = new Query\Builder($this->getKeys());
        $builder->setVisitors([
            new Visitors\EqVisitor($builder),
            new Visitors\NotEqVisitor($builder),
            new Visitors\GtVisitor($builder),
            new Visitors\GteVisitor($builder),
            new Visitors\LtVisitor($builder),
            new Visitors\LteVisitor($builder),
            new Visitors\CtVisitor($builder),
            new Visitors\SwVisitor($builder),
            new Visitors\EwVisitor($builder),
            new Visitors\AndVisitor($builder),
            new Visitors\OrVisitor($builder),
            new Visitors\NotInVisitor($builder),
            new Visitors\InVisitor($builder),
            new Visitors\NullVisitor($builder),
            new Visitors\NotNullVisitor($builder),
        ]);

        return $builder;
    }

    /**
     * Convert the string query into an object (e.g.).
     *
     * @return QueryParser
     */
    public function getParser()
    {
        $parser = new QueryParser();
        $parser->addResolvers([
            new Resolvers\ValueResolver(),
            new Resolvers\KeyResolver(),
            new Resolvers\GroupingResolver(),
            new Resolvers\SumFunctionResolver(),
            new Resolvers\DateFormatFunctionResolver(),
            new Resolvers\NotEqResolver(),
            new Resolvers\EqResolver(),
            new Resolvers\LteResolver(),
            new Resolvers\LtResolver(),
            new Resolvers\GteResolver(),
            new Resolvers\GtResolver(),
            new Resolvers\CtResolver(),
            new Resolvers\SwResolver(),
            new Resolvers\EwResolver(),
            new Resolvers\NotInResolver(),
            new Resolvers\InResolver(),
            new Resolvers\NotNullResolver(),
            new Resolvers\NullResolver(),
            new Resolvers\AndResolver(),
            new Resolvers\OrResolver(),
        ]);

        return $parser;

    }

    /**
     * Filter query with where.
     *
     * @param mixed $query
     * @param string $filter
     *
     * @return void
     */
    public function build($query, $filter)
    {
        $parser = $this->getParser();
        $builder = $this->getBuilder();

        try {
            return $builder->build($query, $parser->parse($filter));
        } catch (\Railken\SQ\Exceptions\QuerySyntaxException $e) {
            throw new \Railken\SQ\Exceptions\QuerySyntaxException($filter);
        }
    }
}
