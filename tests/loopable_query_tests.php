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

}

