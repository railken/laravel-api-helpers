<?php

namespace Railken\Laravel\ApiHelpers;

use Railken\ApiHelpers\Filter as BaseFilter;
use Illuminate\Support\Facades\DB;
use Railken\SQ\QueryParser;
use Railken\SQ\Languages\BoomTree\Resolvers as Resolvers;

class Filter
{   

    /**
     * @var array
     */
    protected $keys;

    /**
     * Closure to parse key
     *
     * @var Closure
     */
    protected $parse_key;

    /**
     * Set keys
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
     * Get keys
     *
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Set parse key
     *
     * @param Closure $parse_key
     *
     * @return $this
     */
    public function setParseKey($parse_key)
    {
        $this->parse_key = $parse_key;

        return $this;
    }

    /**
     * Parse given key before sending to query
     *
     * @param string $key
     *
     * @return mixed
     */
    public function parseKey($key){
        $f = $this->parse_key;

        if ($f && is_callable($f)) { 
            return $f($key);
        }

        return $key;
    }

    /**
     * Filter query with where 
     *
     * @param QueryBuilder $query
     * @param string $filter
     *
     * @return void
     */
    public function build($query, $filter)
    {   
        $builder = new Query\Builder($this->getKeys());
            

        return $builder->build($query, $this->parse($filter));
    }
    
    /**
     * Convert the string query into an object (e.g.)
     *
     * @param string $query (e.g.) title eq 'something'
     *
     * @return Object
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
