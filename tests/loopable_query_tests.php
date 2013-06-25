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
		$this->assertNull( $property->getValue( new Loopable_Query ), 'Loopable_Query did not set the _query property to null when no query was provided!' );
	}

	public function test_count_uses_post_count() {
		$this->markTestIncomplete();
	}

}

