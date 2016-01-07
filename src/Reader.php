<?php

namespace Fewlines\XML;

class Reader extends \XMLReader
{
	/**
	 * @var XML
	 */
	private $xml;

	/**
	 * @param XML $xml
	 */
	public function setXml(XML &$xml) {
		$this->xml = $xml;
	}

	/**
	 * @return array
	 */
	public function getTree(&$parent = null) {
		$tree = array();
		$element = null;

		while ($this->read()) {
			if ($this->nodeType == self::END_ELEMENT) {
				return $tree;
			}

			if ($this->nodeType == self::COMMENT) {
				$tree[] = new Element\Comment($this->value);
			}

			if ($this->nodeType == self::TEXT ||
				$this->nodeType == self::CDATA ||
				$this->nodeType == self::WHITESPACE ||
				$this->nodeType == self::SIGNIFICANT_WHITESPACE && $parent) {
				if ($parent instanceof Element\Base) {
					$parent->setContent($this->value);
					$parent->setCData($this->nodeType == self::CDATA);
				}
			}

			if ($this->nodeType == self::ELEMENT) {
				$element = new Element\Node($this->name);

				if ($this->xml) {
					$element->setXml($this->xml);
				}

				if ($this->hasAttributes) {
					while ($this->moveToNextAttribute()) {
						if ($this->nodeType == \XMLReader::ATTRIBUTE) {
							$element->setAttribute($this->name, $this->value);
						}
					}

					$this->moveToElement();
				}

				if ( ! $this->isEmptyElement) {
					$element->setChildren($this->getTree($element));
				}
			}

			if ($element) {
				$tree[] = $element;
				$element = null;
			}
		}

		return $tree;
	}
}