<?php

namespace Fewlines\XML;

use Fewlines\XML\Element\Saveable;

class XML
{
	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var Element\Node
	 */
	private $root;

	/**
	 * @var array
	 */
	private $tree;

	/**
	 * @var Reader
	 */
	private $reader;

	/**
	 * @var \XMLWriter
	 */
	private $writer;

	/**
	 * @var boolean
	 */
	private $cache;

	/**
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * @var string
	 */
	public $encoding = 'UTF-8';

	/**
	 * @param boolean $cacheEnabled
	 */
	public function __construct($cacheEnabled = true) {
		if ($cacheEnabled) {
			$this->cache = new Cache;
		}

		$this->reader = new Reader;
		$this->writer = new Writer;
	}

	/**
	 * @param  string $url
	 * @return self
	 *
	 * @throws Exception\ReadException
	 * @throws Exception\ParseException
	 */
	public function load($url) {
		if ( ! @$this->reader->open($url)) {
			throw new Exception\ReadException(
				sprintf('Reader could not open url "%s"', $url)
			);
		}

		// Set the url
		$this->url = $url;

		// Set url as file if it's a file
		if (file_exists($this->url)) {
			$this->file = $this->url;
		}

		// Suppress warnings and errors
		libxml_use_internal_errors(true);

		// Check if errors occured
		$errors = libxml_get_errors();
		libxml_clear_errors();

		if (count($errors) > 0) {
			$errMessage = '';

			// Collect warnings/errors
			foreach ($errors as $err) {
				if ($err instanceof \LibXMLError) {
					$errMessage.= $url . ':' . $err->line . ' -> ' . $err->message . "\r\n";
				}
			}

			throw new Exception\ParseException(
				"Something went wrong while parsing: \r\n\r\n" . $errMessage
			);
		}
		else {
			// Set root xml
			$this->reader->setXml($this);

			// Get the tree as array of elements
			$this->tree = $this->reader->getTree();

			// Find the root node to start with
		 	foreach ($this->tree as $element) {
		 		if ($element instanceof Element\Node) {
		 			$this->root = &$element;
		 			break;
		 		}
		 	}

		 	// Check if the root node was found
		 	if ( ! $this->root instanceof Node) {
		 		throw new Exception\InitializeException(
		 			'Root node could not be found'
		 		);
		 	}
		}

		// Close reader
		$this->reader->close();

		return $this;
	}

	/**
	 * Save the current xml trees
	 * to the given url. Only if the url
	 * represents a file
	 *
	 * @param string $resource
	 */
	public function save($resource = '') {
		// Determines which resource will be used
		if (empty($resource)) {
			if (empty($this->file)) {
				throw new Exception\SaveException(
					'No output resource found'
				);
			}
			else {
				$resource = $this->file;
			}
		}

		// Create resource if it doesn't exist
		if ( ! file_exists($resource)) {
			$handle = @fopen($resource, 'w');

			if ( ! $handle) {
				throw new Exception\SaveException(
					sprintf('Something went wrong while creating the file "%s"', $resource)
				);
			}

			fclose($handle);
		}

		// Write the structure to the given resource
		$this->writeTo($resource);
	}

	/**
	 * @param string $resource
	 */
	private function writeTo($resource) {
		$this->writer->openURI($resource);
		$this->writer->startDocument($this->version, $this->encoding);
		$this->writer->setIndent(true);
		$this->writer->setIndentString("\t");

		$this->getRoot()->save($this->writer);

		$this->writer->endDocument();
		$this->writer->flush();
	}

	/**
	 * @return array
	 */
	public function getRoot() {
		return $this->root;
	}

	/**
	 * @return array
	 */
	public function getTree() {
		return $this->tree;
	}

	/**
	 * Returns if the cache is enabled
	 * or not
	 *
	 * @return boolean
	 */
	public function hasCache() {
		return !is_null($this->cache);
	}

	/**
	 * Appends a node to the root node
	 *
	 * @param  Node $node
	 */
	public function append(Node $node) {
		$this->root->append($node);
	}

	/**
	 * @param string $name
	 */
	public function __get($name) {
		if ($this->root) {
			return $this->root->$name;
		}
	}
}