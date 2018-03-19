<?php

namespace Railken\Laravel\ApiHelpers\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class BaseOperatorVisitor
{

	public function visit($query, $node)
	{	

        if ($node instanceof $this->node) {
			$bindings = [];
	        $sql = [];

	        if ($node->getChildByIndex(0) instanceof Nodes\KeyNode) {
	            $sql[] = $node->getChildByIndex(0)->getValue();
	        }

	        if ($node->getChildByIndex(0) instanceof Nodes\ValueNode) {
	            $bindings['p0'] = $node->getChildByIndex(0)->getValue();
	            $sql[] = ':p0';
	        }


	       	$sql[] = $this->operator;


	        if ($node->getChildByIndex(1) instanceof Nodes\KeyNode) {
	            $sql[] = $node->getChildByIndex(1)->getValue();
	        }

	        if ($node->getChildByIndex(1) instanceof Nodes\ValueNode) {
	            $bindings['p1'] = $node->getChildByIndex(1)->getValue();
	            $sql[] = ':p1';
	        }

	        $query->whereRaw(implode(" ", $sql), $bindings);
    	}
	}
}