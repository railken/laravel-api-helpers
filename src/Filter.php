<?php

namespace Railken\Laravel\ApiHelpers;

use Railken\ApiHelpers\Filter as BaseFilter;

class Filter extends BaseFilter
{   

    /**
     * @var array
     */
    protected $keys;

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


        if (!in_array($key, $this->keys))
            return;

        $operator == "in"           && $query->{"{$sub_where}In"}($key, $values);

        $operator == "start_with"   && $query->{"{$sub_where}"}($key, 'like', '%'.$values);
        $operator == "end_with"     && $query->{"{$sub_where}"}($key, 'like', $values.'%');
        $operator == "contains"     && $query->{"{$sub_where}"}($key, 'like', '%'.$values.'%');

        $operator == "eq"           && $query->{"{$sub_where}"}($key, '=', $values);
        $operator == "gt"           && $query->{"{$sub_where}"}($key, '>', $values);
        $operator == "lt"           && $query->{"{$sub_where}"}($key, '<', $values);
    }
}
