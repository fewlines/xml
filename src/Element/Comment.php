<?php

namespace Fewlines\XML\Element;

use Fewlines\XML\Writer;

class Comment extends Base
{
	/**
	 * @var string
	 */
	private $value = '';

	/**
	 * @param string $value
	 */
	public function __construct($value = '') {
		parent::__construct();

		$this->setValue($value);
	}

	/**
	 * @return string
	 */
	public function getValue() {
    	return $this->value;
	}

	/**
	 * @param string $value
	 * @return self
	 */
	public function setValue($value) {
    	$this->value = $value;

    	return $this;
	}

	/**
	 * @param Writer &$writer
	 */
	public function save(Writer &$writer) {
		$writer->writeComment($this->value);
	}
}