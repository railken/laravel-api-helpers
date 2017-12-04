<?php

namespace Railken\Laravel\ApiHelpers;

use Railken\ApiHelpers\Filter as BaseFilter;
use Illuminate\Support\Facades\DB;

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
     * Build query builder using node
     *
     * @param QueryBuilder $query
     * @param FilterNode $node
     *
     * @return void
     */
    public function buildQuery($query, $node, $last_logic_operator = 'and')
    {

        if (is_array($node)) {
            foreach($node as $expression)
                $this->buildQuery($query, $expression, $last_logic_operator);

            return;
        }   

        $values = $node->getValue();
        $operator = $node->getOperator();

        $key = $node->getKey() ? $node->getKey() : null;



        $sub_where = ((object)['and' => 'where', 'or' => 'orWhere'])->$last_logic_operator;


        $operator == "or"           && $query->{"{$sub_where}"}(function($sub_query) use ($values) {
            $this->buildQuery($sub_query, $values, "or");
        });

        $operator == "and"          && $query->{"{$sub_where}"}(function($sub_query) use ($values) {
             $this->buildQuery($sub_query, $values, "and");
        });


        if (!in_array($key, $this->keys)) {
            throw new Exceptions\FilterUndefinedKeyException($key);
        }
        
        $key = $this->parseKey($key);


        $operator == "in"           && $query->{"{$sub_where}In"}($key, $values);

        $operator == "sw"           && $query->{"{$sub_where}"}($key, 'like', '%'.$values);
        $operator == "ew"           && $query->{"{$sub_where}"}($key, 'like', $values.'%');
        $operator == "ct"           && $query->{"{$sub_where}"}($key, 'like', '%'.$values.'%');

        $operator == "eq"           && $query->{"{$sub_where}"}($key, '=', $values);
        $operator == "gt"           && $query->{"{$sub_where}"}($key, '>', $values);
        $operator == "gte"          && $query->{"{$sub_where}"}($key, '>=', $values);
        $operator == "lt"           && $query->{"{$sub_where}"}($key, '<', $values);
        $operator == "lte"          && $query->{"{$sub_where}"}($key, '<', $values);
    }
}
