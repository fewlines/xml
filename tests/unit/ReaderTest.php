<?php
namespace Fewlines\Test\XML;

use Fewlines\XML\XML;

class ReaderTest extends \PHPUnit_Framework_TestCase
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

    public function testWithoutCacheXML() {
        $this->assertFalse($this->xmlWithoutCache->hasCache());
    }

    public function testWithCacheXML() {
        $this->assertTrue($this->xmlWithCache->hasCache());
    }

    public function testValidPathLoading() {
        $this->xmlLoad(realpath(__DIR__ . '/../assets/xml-reader-test-valid.xml'));
    }

    /**
     * @expectedException \Fewlines\XML\Exception\ReadException
     */
    public function testInvalidPathLoading() {
        $this->xmlLoad(__DIR__ . '/assets/xml-reader-test-2l2ljdk2ldsks.xml');
    }

    /**
     * @expectedException \Fewlines\XML\Exception\ParseException
     */
    public function testInvalidStructureLoading() {
        $this->xmlLoad(realpath(__DIR__ . '/../assets/xml-reader-test-invalid.xml'));
    }
}
