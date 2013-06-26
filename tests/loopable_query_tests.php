<?php

class Loopable_Query_Tests extends PHPUnit_Framework_TestCase {

	/**
	 * @var Loopable_Query
	 */
	private $object;

	public function setUp() {
		WP_Mock::setUp();
		global $wp_query;
		unset( $wp_query );
	}

	public function tearDown() {
		WP_Mock::tearDown();
		global $wp_query;
		unset( $wp_query );
	}

	public function test_object_implements_interfaces() {
		$object = new Loopable_Query();
		$this->assertInstanceOf( 'Countable', $object, 'Loopable_Query does not implement the Countable interface!' );
		$this->assertInstanceOf( 'Iterator', $object, 'Loopable_Query does not implement the Iterator interface!' );
	}

	public function test_object_sets_query_from_constructor() {
		$query  = Mockery::mock( 'WP_Query' );
		$object = new Loopable_Query( $query );

		$property = new ReflectionProperty( $object, '_query' );
		$property->setAccessible( true );

		$this->assertSame( $query, $property->getValue( $object ), 'Loopable_Query did not correctly set the query object!' );

		$GLOBALS['wp_query'] = $global_query = Mockery::mock( 'WP_Query' );
		$this->assertSame( $global_query, $property->getValue( new Loopable_Query ), 'Loopable_Query did not set the _query property to the global query object when no query was provided!' );
	}

	public function test_count_uses_post_count() {
		$query             = Mockery::mock( 'WP_Query' );
		$query->post_count = $expected_count = rand( 0, 10000 );

		$object = new Loopable_Query( $query );

		$this->assertSame( $expected_count, $object->count(), 'count() method did not return the correct number!' );
		$this->assertSame( $expected_count, count( $object ), 'count() method did not return the correct number!' );
	}

	public function test_rewind() {
		$query              = Mockery::mock( 'WP_Query' );
		$query->in_the_loop = true;
		$query->shouldReceive( 'rewind_posts' )->once();
		WP_Mock::wpFunction( 'wp_reset_postdata', array(
			'times' => 1
		) );

		$object = new Loopable_Query( $query );
		$object->rewind();

		$this->assertFalse( $query->in_the_loop );
	}

	public function test_valid_not_in_loop() {
		$query              = Mockery::mock( 'WP_Query' );
		$query->in_the_loop = false;
		$query->shouldReceive( 'have_posts' )->once()->andReturn( true );
		$query->shouldReceive( 'rewind_posts' )->never();
		$query->shouldReceive( 'the_post' )->once();
		WP_Mock::wpFunction( 'wp_reset_postdata', array( 'times' => 0 ) );

		$object = new Loopable_Query( $query );
		$this->assertTrue( $object->valid(), 'valid() did not return true when have_posts() did!' );
	}

	public function test_valid_already_in_loop() {
		$query              = Mockery::mock( 'WP_Query' );
		$query->in_the_loop = true;
		$query->shouldReceive( 'have_posts' )->once()->andReturn( true );
		$query->shouldReceive( 'rewind_posts' )->never();
		$query->shouldReceive( 'the_post' )->never();
		WP_Mock::wpFunction( 'wp_reset_postdata', array( 'times' => 0 ) );

		$object = new Loopable_Query( $query );
		$this->assertTrue( $object->valid(), 'valid() did not return true when have_posts() did!' );
	}

	public function test_not_valid() {
		$query              = Mockery::mock( 'WP_Query' );
		$query->in_the_loop = true;
		$query->shouldReceive( 'have_posts' )->once()->andReturn( false );
		$query->shouldReceive( 'rewind_posts' )->once();
		$query->shouldReceive( 'the_post' )->never();
		WP_Mock::wpFunction( 'wp_reset_postdata', array( 'times' => 1 ) );

		$object = new Loopable_Query( $query );
		$this->assertFalse( $object->valid(), 'valid() did not return true when have_posts() did!' );
	}

	public function test_next() {
		$query = Mockery::mock( 'WP_Query' );
		$query->shouldReceive( 'the_post' )->once();
		$query->post_count   = rand( 5, 10 );
		$query->current_post = $query->post_count - 2;

		$object = new Loopable_Query( $query );
		$object->next();
	}

	public function test_no_next() {
		$query = Mockery::mock( 'WP_Query' );
		$query->shouldReceive( 'the_post' )->never();
		$query->post_count   = rand( 5, 10 );
		$query->current_post = $query->post_count - 1;

		$object = new Loopable_Query( $query );
		$object->next();
	}

	public function test_key() {
		$query               = Mockery::mock( 'WP_Query' );
		$query->current_post = $expected_key = rand( 1, 1000 );
		$object              = new Loopable_Query( $query );
		$this->assertEquals( $expected_key, $object->key() );
	}

}

