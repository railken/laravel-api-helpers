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

    public function generateCallTrace()
{
    $e = new \Exception();
    $trace = explode("\n", $e->getTraceAsString());
    // reverse array to make steps line up chronologically
    $trace = array_reverse($trace);
    array_shift($trace); // remove {main}
    array_pop($trace); // remove call to this method
    $length = count($trace);
    $result = array();
    
    for ($i = 0; $i < $length; $i++)
    {
        $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
    }
    
    return "\t" . implode("\n\t", $result);
}
}