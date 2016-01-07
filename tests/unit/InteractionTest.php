<?php

namespace Fewlines\Test\XML;

use Fewlines\XML\XML;

class InteractionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var XML
	 */
	private $xmlWithoutCache;

	/**
	 * @var XML
	 */
	private $xmlWithCache;

    /**
     * Test setup
     */
    protected function setUp() {
    	$this->xmlWithCache = new XML();
    	$this->xmlWithoutCache = new XML(false);
    }

    /**
     * @param string $file
     */
    public function xmlLoad($file) {
    	$this->xmlWithCache->load($file);
    	$this->xmlWithoutCache->load($file);
    }

    public function loadStaticXml() {
    	$this->xmlLoad(realpath(__DIR__ . '/../assets/xml-reader-test-static.xml'));
    }

    public function loadValidXml() {
    	$this->xmlLoad(realpath(__DIR__ . '/../assets/xml-reader-test-valid.xml'));
    }

    public function testStaticReadRootNotNull() {
    	$this->loadStaticXml();

	    $this->assertNotNull($this->xmlWithCache->getRoot());
	    $this->assertNotNull($this->xmlWithoutCache->getRoot());
    }

    public function testStaticReadCountChildrenRoot() {
    	$this->loadStaticXml();

    	$expectedCount = 3;

    	$childrenCount = $this->xmlWithCache->getRoot()->count();
    	$this->assertEquals($childrenCount, $expectedCount);

    	$childrenCount = $this->xmlWithoutCache->getRoot()->count();
    	$this->assertEquals($childrenCount, $expectedCount);
    }

    public function testStaticNodeChildrenTraversable() {
    	$this->loadStaticXml();

    	$expectedCount = 3;

    	$traversable = 0;
    	foreach ($this->xmlWithoutCache->getRoot() as $child) {
    		$traversable++;
    	}

    	$this->assertEquals($traversable, $expectedCount);

    	$traversable = 0;
    	foreach ($this->xmlWithCache->getRoot() as $child) {
    		$traversable++;
    	}

    	$this->assertEquals($traversable, $expectedCount);
    }

    public function testStaticReadCountChildrenNode() {
    	$this->loadStaticXml();

    	$expectedCount = 6;

		$childrenCount = $this->xmlWithoutCache->test[0]->testsubchildren[0]->count();
		$this->assertEquals($expectedCount, $childrenCount);

		$childrenCount = $this->xmlWithCache->test[0]->testsubchildren[0]->count();
		$this->assertEquals($expectedCount, $childrenCount);
    }

    public function testStaticNodeCdataContent() {
        $this->loadStaticXml();

        $node = $this->xmlWithoutCache->test[2];
        $this->assertTrue($node->isCData());

        $node = $this->xmlWithCache->test[2];
        $this->assertTrue($node->isCData());
    }

    public function testStaticNodeContent() {
    	$this->loadStaticXml();

    	$expectedContent = 'Do not change anything';
    	$expectedContentCdata = 'Do not change anything';

        $contentNode = $this->xmlWithCache->test[1];
    	$contentCdataNode = $this->xmlWithCache->test[2];

    	$this->assertEquals($contentNode->getContent(), $expectedContent);
    	$this->assertEquals($contentCdataNode->getContent(), $expectedContentCdata);

    	$contentNode = $this->xmlWithoutCache->test[1];
    	$contentCdataNode = $this->xmlWithoutCache->test[2];

    	$this->assertEquals($contentNode->getContent(), $expectedContent);
    	$this->assertEquals($contentCdataNode->getContent(), $expectedContentCdata);
    }

    public function testXMLObjectDeclarationAttributes() {
        $this->assertEquals($this->xmlWithCache->version, '1.0');
        $this->assertEquals($this->xmlWithoutCache->version, '1.0');

        $this->assertEquals($this->xmlWithCache->encoding, 'UTF-8');
        $this->assertEquals($this->xmlWithoutCache->encoding, 'UTF-8');
    }
}