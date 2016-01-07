<?php

namespace Fewlines\XML\Element;

use Fewlines\XML\Writer;

interface Saveable
{
	/**
	 * @param Writer &$writer
	 */
	public function save(Writer &$writer);
}