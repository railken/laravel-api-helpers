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
        $query = (new Foo)->newQuery()->getQuery();
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
       $filter->build((new Foo)->newQuery()->getQuery(), 'y eq 1');
    } 

	public function testFilterEq()
	{
        $this->assertEquals('select * from `foo` where `x` = :p1', $this->newQuery('x eq 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` = :p1', $this->newQuery('x = 1')->toSql());
	}

    public function testFilterGt()
    {
        $this->assertEquals('select * from `foo` where `x` > :p1', $this->newQuery('x gt 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` > :p1', $this->newQuery('x > 1')->toSql());
    }

    public function testFilterGte()
    {
        $this->assertEquals('select * from `foo` where `x` >= :p1', $this->newQuery('x gte 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` >= :p1', $this->newQuery('x >= 1')->toSql());
    }

    public function testFilterLt()
    {
        $this->assertEquals('select * from `foo` where `x` < :p1', $this->newQuery('x lt 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` < :p1', $this->newQuery('x < 1')->toSql());
    }

    public function testFilterLte()
    {
        $this->assertEquals('select * from `foo` where `x` <= :p1', $this->newQuery('x lte 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` <= :p1', $this->newQuery('x <= 1')->toSql());
    }

    public function testFilterCt()
    {
        $this->assertEquals('select * from `foo` where `x` like :p1', $this->newQuery('x ct 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` like :p1', $this->newQuery('x *= 1')->toSql());
    }

    public function testFilterSw()
    {
        $this->assertEquals('select * from `foo` where `x` like :p1', $this->newQuery('x sw 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` like :p1', $this->newQuery('x ^= 1')->toSql());
    }

    public function testFilterEw()
    {
        $this->assertEquals('select * from `foo` where `x` like :p1', $this->newQuery('x ew 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` like :p1', $this->newQuery('x $= 1')->toSql());
    }

    public function testFilterIn()
    {
        $this->assertEquals('select * from `foo` where `x` in (:p1)', $this->newQuery('x in (1)')->toSql());
        $this->assertEquals('select * from `foo` where `x` in (:p1)', $this->newQuery('x =[] (1)')->toSql());
    }
     
    public function testFilterAnd()
    {
        $this->assertEquals('select * from `foo` where (`x` = :p1 and `x` = :p1)', $this->newQuery('x = 1 and x = 2')->toSql());
        $this->assertEquals('select * from `foo` where (`x` = :p1 and `x` = :p1)', $this->newQuery('x = 1 && x = 2')->toSql());
    }
     
    public function testFilterOr()
    {
        $this->assertEquals('select * from `foo` where (`x` = :p1 or `x` = :p1)', $this->newQuery('x = 1 or x = 2')->toSql());
        $this->assertEquals('select * from `foo` where (`x` = :p1 or `x` = :p1)', $this->newQuery('x = 1 || x = 2')->toSql());
    }

    public function testBasic()
    {   
        $this->assertEquals(Sorter::class, get_class(new Sorter()));
        $this->assertEquals(Paginator::class, get_class(new Paginator()));
    }
}