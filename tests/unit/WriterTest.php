<?php
namespace Fewlines\Test\XML;

use Fewlines\XML\XML;

class WriterTest extends \PHPUnit_Framework_TestCase
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
     * Seek and destroy \m/
     */
    protected function tearDown() {
    	// Remove xml-reader-test-save-extern.xml
    	// Testing file_exists, because of the realpath
    	// function will cache the paths and will not
    	// recognize if the file was removed
    	$xmlReaderTestSaveExtern = realpath(__DIR__ . '/../assets/xml-reader-test-save-extern.xml');

    	if ($xmlReaderTestSaveExtern && file_exists($xmlReaderTestSaveExtern)) {
    		@unlink($xmlReaderTestSaveExtern);
    	}
    }

    /**
     * @param string $file
     */
    public function xmlLoad($file) {
    	$this->xmlWithCache->load($file);
    	$this->xmlWithoutCache->load($file);
    }

    /**
     * @expectedException \Fewlines\XML\Exception\SaveException
     */
    public function testSaveInvalidFileWithCache() {
    	$this->xmlLoad('http://www.w3schools.com/xml/cd_catalog.xml');

    	$this->xmlWithCache->save();
    }

    /**
     * @expectedException \Fewlines\XML\Exception\SaveException
     */
    public function testSaveInvalidFileWithoutCache() {
    	$this->xmlLoad('http://www.w3schools.com/xml/cd_catalog.xml');

    	$this->xmlWithoutCache->save();
    }

    public function testValidFileSave() {
    	$this->xmlLoad(realpath(__DIR__ . '/../assets/xml-reader-test-save.xml'));

    	$this->xmlWithCache->save();
    	$this->xmlWithoutCache->save();
    }

    public function testValidFileChangeSave() {
    	$this->xmlLoad(realpath(__DIR__ . '/../assets/xml-reader-test-save.xml'));

    	$this->xmlWithCache->getRoot()->savetest[0]->setContent('Writing works!');
    	$this->xmlWithCache->save();

    	$this->xmlWithoutCache->getRoot()->savetest[0]->setContent('Writing works!');
    	$this->xmlWithoutCache->save();
    }

    /**
     * @expectedException \Fewlines\XML\Exception\SaveException
     */
    public function testValidFileSaveWithInvalidPathWithCache() {
    	$this->xmlLoad(realpath(__DIR__ . '/../assets/xml-reader-test-save.xml'));

    	$this->xmlWithCache->save('../invalid/path');
    }

    /**
     * @expectedException \Fewlines\XML\Exception\SaveException
     */
    public function testValidFileSaveWithInvalidPathWithoutCache() {
    	$this->xmlLoad(realpath(__DIR__ . '/../assets/xml-reader-test-save.xml'));

    	$this->xmlWithoutCache->save('../invalid/path');
    }

    public function testValidExternFileSaveWithCache() {
    	$this->xmlLoad('http://www.w3schools.com/xml/cd_catalog.xml');

    	$this->xmlWithCache->save('tests/assets/xml-reader-test-save-extern.xml');
    }

    public function testValidExternFileSaveWithoutCache() {
    	$this->xmlLoad('http://www.w3schools.com/xml/cd_catalog.xml');

    	$this->xmlWithoutCache->save('tests/assets/xml-reader-test-save-extern.xml');
    }
}
