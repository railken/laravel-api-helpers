<?php

use PHPUnit\Framework\TestCase;
use Railken\Laravel\ApiHelpers\Filter;
use Railken\Laravel\ApiHelpers\Sorter;
use Railken\Laravel\ApiHelpers\Paginator;
use Railken\Laravel\ApiHelpers\Tests\User;


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
     * @expectedException Railken\Laravel\ApiHelpers\Exceptions\FilterUndefinedKeyException
     */
    public function testFilterUndefindKey()
    {
       $filter = new Filter();
       $filter->setKeys(['x']);
       $filter->build(User::where('id', 0), 'y eq 1');
    } 


	public function testFilter()
	{
        $this->assertEquals(Filter::class, get_class(new Filter()));
	}
    public function testBasic()
    {   
        $this->assertEquals(Sorter::class, get_class(new Sorter()));
        $this->assertEquals(Paginator::class, get_class(new Paginator()));
    }
}