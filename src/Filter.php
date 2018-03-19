<?php

namespace Railken\Laravel\ApiHelpers;

use Railken\ApiHelpers\Filter as BaseFilter;
use Illuminate\Support\Facades\DB;
use Railken\SQ\QueryParser;
use Railken\SQ\Languages\BoomTree\Resolvers as Resolvers;

class Filter extends BaseFilter
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

        $node = $this->parse($filter);

        return $node ? $this->buildQuery($query, $node) : null;
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
            new Resolvers\NotInResolver(),
            new Resolvers\InResolver(),
            new Resolvers\EwResolver(),
            new Resolvers\NotNullResolver(),
            new Resolvers\NullResolver(),
            new Resolvers\AndResolver(),
            new Resolvers\OrResolver(),
        ]);

        return $parser->parse($query);
    }


    /**
     * Build query builder using node
     *
     * @param QueryBuilder $query
     * @param FilterNode $node
     *
     * @return void
     */
    public function buildQuery($query, $node)
    {
        $visitors = [
            new Visitors\EqVisitor(),
            new Visitors\NotEqVisitor(),
            new Visitors\LogicOperatorVisitor(function($query, $node) {
                return $this->buildQuery($query, $node);
            }),
        ];

       
        foreach ($visitors as $visitor) {
            $visitor->visit($query, $node);
        }

        /*
        if (is_array($node)) {
            foreach($node as $expression)
                $this->buildQuery($query, $expression, $last_logic_operator);

            return;
        }   

        $operator = $node->getOperator();


        $sub_where = ((object)['and' => 'where', 'or' => 'orWhere'])->$last_logic_operator;


        $operator == "or"           && $query->{"{$sub_where}"}(function($sub_query) use ($node) {
            $this->buildQuery($sub_query, $node->getChilds(), "or");
        });

        $operator == "and"          && $query->{"{$sub_where}"}(function($sub_query) use ($node) {
             $this->buildQuery($sub_query, $node->getChilds(), "and");
        });

        if ($operator === 'or' || $operator === 'and')
            return;


        $key = $node->getKey() ? $node->getKey() : null;
        $values = $node->getValue();

        if (!in_array($key, $this->keys)) {
            throw new Exceptions\FilterUndefinedKeyException($key);
        }
        
        $key = $this->parseKey($key);
        */
    }
}
