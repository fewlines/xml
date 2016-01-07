<?php

namespace Fewlines\XML\Element;

abstract class Base implements Saveable
{
	/**
	 * @var string
	 */
	const ID_PREFIX = 'el';

	/**
	 * @var string
	 */
	private $id;

	/**
	 * Set unique id call this constructor
	 * in the child class
	 */
	public function __construct() {
		$this->id = uniqid(self::ID_PREFIX);
	}

	/**
	 * @return string
	 */
	public function getId() {
    	return $this->id;
	}
}