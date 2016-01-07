<?php

// Autoloader
require realpath(__DIR__ . '/../vendor/autoload.php');

// Uses
use Fewlines\XML\XML;
use Fewlines\XML\Element\Node;

/**
 * Fewlines XML tests
 */

$xml = new XML();
$xml->load(__DIR__ . '/assets/xml-reader-test-valid.xml');
$xml->getRoot()->append(
	new Node('testappend', [
		new Node('testnodechild'),
		new Node('testnodechild'),
		new Node('testnodechild'),
		new Node('testnodechild'),
		new Node('testnodechild'),
		new Node('testnodechild'),
		new Node('testnodechild')
	])
);
$xml->save();

/**
 * SimpleXMLElement tests
 */

// $xml = new \SimpleXMLElement(__DIR__ . '/assets/xml-reader-test-valid.xml', 0, true);
// print_r($xml->xpath('testing/general'));