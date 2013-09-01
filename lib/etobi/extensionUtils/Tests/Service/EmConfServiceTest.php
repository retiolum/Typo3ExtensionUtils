<?php

namespace etobi\extensionUtils\Tests\Service;

use etobi\extensionUtils\Service\EmConfService;
use etobi\extensionUtils\Model\EmConf;

class ExtTest extends \PHPUnit_Framework_TestCase {

    protected $tempFiles = array();
	protected $testExtEmConfFile = '';

	public function setUp() {
		$this->testExtEmConfFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'test_ext_emconf.php';
	}

    public function tearDown() {
        foreach($this->tempFiles as $tempFile) {
            unlink($tempFile);
        }
    }

    protected function getTempfileName($ext = NULL) {
        $fileName = tempnam(sys_get_temp_dir(), 'test');
        if($ext) {
            $fileName .= '.' . $ext;
        }
        $this->tempFiles[] = $fileName;
        return $fileName;
    }


    public function testFileReader() {
	    $emConfService = new EmConfService();
        $emconf = $emConfService->readFile($this->testExtEmConfFile);

        $this->assertSame(
            'Extension Builder',
            $emconf->getTitle(),
            'configuration array read'
        );

        $this->assertContains(
            'config file for ext',
            $emconf->getComment(),
            'comment read'
        );
    }

    public function testStringReader() {
	    $emConfService = new EmConfService();
        $emconf = $emConfService->readString(file_get_contents($this->testExtEmConfFile));

        $this->assertSame(
            'Extension Builder',
            $emconf->getTitle(),
            'configuration array read'
        );

        $this->assertContains(
            'config file for ext',
            $emconf->getComment(),
            'comment read'
        );
    }

    public function testToString() {
	    $emConfService = new EmConfService();
        $emconf = $emConfService->readFile($this->testExtEmConfFile);

        $string = $emConfService->writeString($emconf);

        $this->assertStringStartsWith('<?php', $string, 'string starts with an opening PHP tag');
        $this->assertStringEndsWith('?>', $string, 'string ends with a closing PHP tag');
        $this->assertContains('/*', $string, 'contains a comment');
        $this->assertContains('$EM_CONF[$_EXTKEY] = array (', $string, 'contains a configuration array');
        $this->assertContains("'title' => 'Extension Builder',", $string, 'contains configuration');
        $this->assertNotRegExp('/^  /ms', $string, 'indentation is not done with spaces');
    }

    public function testWriteFile() {
        $fileName = $this->getTempfileName('php');
        copy($this->testExtEmConfFile, $fileName);

        $originalContent = file($fileName);

	    $emConfService = new EmConfService();
	    $emconf = $emConfService->readFile($fileName);
        // set content to something to see if writeFile actually writes something
        file_put_contents($fileName, 'test failed');
        $emConfService->writeFile($emconf, $fileName);

        $this->assertNotContains('test failed', file_get_contents($fileName), 'writeFile() wrote a file');
        $emconf2 = $emConfService->readFile($fileName);
        $this->assertSame($emconf->getTitle(), $emconf2->getTitle(), 'EmConf can read array of its created files');
        $this->assertSame($emconf->getComment(), $emconf2->getComment(), 'EmConf can read comment of its created files');

        $modifiedContent = file($fileName);

        $this->assertSame(
            $originalContent,
            $modifiedContent,
            'writeFile does not change the content of a well formed ext_emconf.php if nothing was changed'
            // necessary for version control
        );

    }


}