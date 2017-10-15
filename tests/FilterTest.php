<?php

use PHPUnit\Framework\TestCase;
use Railken\Laravel\ApiHelpers\Filter;
use Railken\Laravel\ApiHelpers\Sorter;
use Railken\Laravel\ApiHelpers\Paginator;


class FilterTest extends TestCase
{

    public function testBasic()
    {   
        $this->assertEquals(Filter::class, get_class(new Filter()));
        $this->assertEquals(Sorter::class, get_class(new Sorter()));
        $this->assertEquals(Paginator::class, get_class(new Paginator()));
    }
}