<?php

use PHPUnit\Framework\TestCase;
use Railken\Laravel\ApiHelpers\Filter;
use Railken\Laravel\ApiHelpers\Sorter;
use Railken\Laravel\ApiHelpers\Paginator;
use Railken\Laravel\ApiHelpers\Tests\Foo;


class FilterTest extends \Orchestra\Testbench\TestCase
{
	  /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    protected function getPackageProviders($app)
    {
        return [

        ];
    }

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__."/..", '.env');
        $dotenv->load();
        parent::setUp();
    }


    /**
     * Retrieve a new instance of query
     *
     * @param string $str_filter
     *
     * @return QueryBuilder
     */
    public function newQuery($str_filter)
    {

        $filter = new Filter();
        $filter->setKeys(['x']);
        $query = Foo::query();

        echo $str_filter;
        $filter->build($query, $str_filter);
        return $query;
    }
    

    /**
     * @expectedException Railken\Laravel\ApiHelpers\Exceptions\FilterUndefinedKeyException
     */
    public function testFilterUndefindKey()
    {
       $filter = new Filter();
       $filter->setKeys(['x']);
       $filter->build(Foo::query(), 'y eq 1');
    } 

	public function testFilterEq()
	{
        $this->assertEquals('select * from `foo` where `x` = ?', $this->newQuery('x eq 1')->toSql());
	}

    public function testFilterGt()
    {
        $this->assertEquals('select * from `foo` where `x` > ?', $this->newQuery('x gt 1')->toSql());
    }

    public function testFilterGte()
    {
        $this->assertEquals('select * from `foo` where `x` >= ?', $this->newQuery('x gte 1')->toSql());
    }
    public function testBasic()
    {   
        $this->assertEquals(Sorter::class, get_class(new Sorter()));
        $this->assertEquals(Paginator::class, get_class(new Paginator()));
    }
}