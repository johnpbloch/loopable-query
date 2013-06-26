<?php

class Loopable_Query implements Countable, Iterator {

	/**
	 * @var WP_Query
	 */
	protected $_query;

	public function __construct( WP_Query $query = null ) {
		$this->_query = $query ? $query : $GLOBALS['wp_query'];
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 *       The return value is cast to an integer.
	 */
	public function count() {
		return (int) $this->_query->post_count;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 */
	public function current() {
		// TODO: Implement current() method.
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 */
	public function next() {
		if ( $this->_query->current_post + 1 < $this->_query->post_count ) {
			$this->_query->the_post();
		}
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key() {
		return $this->_query->current_post;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 *       Returns true on success or false on failure.
	 */
	public function valid() {
		if ( $this->_query->have_posts() ) {
			if ( ! $this->_query->in_the_loop ) {
				$this->_query->the_post();
			}
			return true;
		}
		$this->_query->rewind_posts();
		wp_reset_postdata();
		return false;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 */
	public function rewind() {
		wp_reset_postdata();
		$this->_query->rewind_posts();
		$this->_query->in_the_loop = false;
	}

}

