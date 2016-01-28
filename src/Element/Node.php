<?php

namespace Fewlines\XML\Element;

use Fewlines\XML\XML;
use Fewlines\XML\Writer;
use Fewlines\XML\Element\Saveable;

class Node extends Base implements Traversable
{
	/**
	 * @var XML
	 */
	private $xml;

	/**
	 * @var array
	 */
	private $attributes = array();

	/**
	 * @var string
	 */
	private $name = '';

	/**
	 * @var string
	 */
	private $content = '';

	/**
	 * @var boolean
	 */
	private $cData = false;

	/**
	 * @var array
	 */
	private $children = array();

	/**
	 * @param string       $name
	 * @param string|array $content
	 * @param array        $attributes
	 */
	public function __construct($name, $content = null, $attributes = array()) {
		parent::__construct();

		// Sets the tagname
		$this->setName($name);

		// Determines if content is a string
		// or other child nodes which will
		// be appended
		if (is_string($content)) {
			$this->setContent($content);
		}
		else if (is_array($content)) {
			foreach ($content as $child) {
				if ($child instanceof Node) {
					$this->append($child);
				}
			}
		}

		// Add the attributes
		if ( ! empty($attributes)) {
			$this->setAttributes($attributes);
		}
	}

	/**
	 * @return integer
	 */
	public function count() {
		return count($this->children);
	}

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator($this->children);
	}

	/**
	 * @return XML
	 */
	public function getXml() {
    	return $this->xml;
	}

	/**
	 * @param XML $xml
	 * @return self
	 */
	public function setXml(XML &$xml) {
    	//$this->xml = $xml;

    	return $this;
	}

	/**
	 * @return array
	 */
	public function getAttributes() {
    	return $this->attributes;
	}

	/**
	 * @param array $attributes
	 * @return self
	 */
	public function setAttributes($attributes) {
    	$this->attributes = $attributes;

    	return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}

	/**
	 * @param array $children
	 */
	public function setChildren($children) {
		$this->children = $children;

    	return $this;
	}

	/**
	 * @return array
	 */
	public function getChildren() {
    	return $this->children;
	}

	/**
	 * @return string
	 */
	public function getName() {
    	return $this->name;
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public function setName($name) {
    	$this->name = $name;

    	return $this;
	}

	/**
	 * @return string
	 */
	public function getContent() {
    	return $this->content;
	}

	/**
	 * @param string $content
	 * @return self
	 */
	public function setContent($content) {
    	$this->content = $content;

    	return $this;
	}

	/**
	 * @return boolean
	 */
	public function isCData() {
    	return $this->cData;
	}

	/**
	 * @param boolean $cData
	 * @return self
	 */
	public function setCData($cData) {
    	$this->cData = $cData;

    	return $this;
	}

	/**
	 * @param Node $child
	 * @return self
	 */
	public function append(Node $child) {
		if ($this->xml) {
			$child->setXml($this->xml);
		}

		$this->children[] = $child;
	}


	/**
	 * @param Node $child
	 * @return self
	 */
	public function prepend(Node $child) {
		if ($this->xml) {
			$child->setXml($this->xml);
		}

		array_unshift($this->children, $child);
	}

	/**
	 * @return Node
	 */
	public function __get($name) {
		$children = array();

		foreach ($this->children as $child) {
			if ($child instanceof Node) {
				if ($child->getName() == $name) {
					$children[] = $child;
				}
			}
		}

		return $children;
	}

	/**
	 * @param Writer &$writer
	 */
	public function save(Writer &$writer) {
		if ($this instanceof Saveable) {
			$writer->startElement($this->name);

			// Write data
			if ( ! empty($this->content) && empty($this->children)) {
				if ($this->isCData()) {
					$writer->writeCData($this->content);
				}
				else {
					$writer->text($this->content);
				}
			}

			print_r($this->attributes);

			// Write attributes
			foreach ($this->attributes as $name => $value) {
				$writer->writeAttribute($name, $value);
	  		}

	        // Writer children
			foreach ($this->children as $child) {
				$child->save($writer);
			}

			$writer->endElement();
		}
	}
}