<?php

namespace Railken\Laravel\ApiHelpers;

use Railken\ApiHelpers\Paginator as BasePaginator;

class Paginator extends BasePaginator
{

	/**
	 * Count records query
	 *
	 * @param $query
	 *
	 * @return integer
	 */
	public function count($query)
	{
		return $query->count();
	}
}
