<?php

namespace Railken\Laravel\ApiHelpers;

use Railken\SQ\Languages\BoomTree\Resolvers as Resolvers;
use Railken\SQ\QueryParser;

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
     * @param \Illuminate\Database\Query\Builder $query
     * @param string                             $filter
     *
     * @return void
     */
    public function build($query, $filter)
    {
        $builder = new Query\Builder($this->getKeys());
        $builder->build($query, $this->parse($filter));
    }

    /**
     * Convert the string query into an object (e.g.).
     *
     * @param string $query (e.g.) title eq 'something'
     *
     * @return object
     */
    public function parse($query)
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

        return $parser->parse($query);
    }
}
