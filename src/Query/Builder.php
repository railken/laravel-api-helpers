<?php

namespace Railken\Laravel\ApiHelpers\Query;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class Builder
{

	protected $context;

	public function setContext($context)
	{
		$this->context = $context;
	}

	public function getContext()
	{
		return $this->context;
	}

	public function __construct()
	{

		$this->context = Nodes\AndNode::class;

        $this->visitors = [
            new Visitors\EqVisitor($this),
            new Visitors\NotEqVisitor($this),
            new Visitors\AndVisitor($this),
            new Visitors\OrVisitor($this),
        ];


        /*if (!in_array($key, $this->keys)) {
            throw new Exceptions\FilterUndefinedKeyException($key);
        }*/
	}

	public function build($query, $node, $context = Nodes\AndNode::class)
	{
        foreach ($this->visitors as $visitor) {
            $visitor->visit($query, $node, $context);
        }
    }
}