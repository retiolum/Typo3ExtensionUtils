<?php

namespace etobi\extensionUtils\Tests\Model;

use etobi\extensionUtils\Model\EmConf;

class EmConfTest extends \PHPUnit_Framework_TestCase {

    protected $defaultData = array (
        'title' => 'Foobar 42',
        'description' => 'Lorem ipsum sid dolor',
        'category' => 'module',
        'shy' => 0,
        'version' => '1.2.3',
        'priority' => '',
        'state' => 'beta',
        'clearcacheonload' => 0,
        'author' => 'John Doe',
        'author_email' => 'doe@example.com',
        'author_company' => 'Foobar Corp.',
    );

    /**
     * @var EmConf
     */
    protected $emConf;

    public function setUp() {
        $this->emConf = new EmConf($this->defaultData);
    }

    public function testTitle() {
        $this->assertSame(
            'Foobar 42',
            $this->emConf->getTitle(),
            'get title from given array'
        );

        $this->emConf->setTitle('Foobar 43');

        $this->assertSame(
            'Foobar 43',
            $this->emConf->getTitle(),
            'title set in setTitle is available in getTitle'
        );

	    $emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'title',
            $emConfArray,
            'title set in setTitle is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['title'],
            'Foobar 43',
            'title set in setTitle is available in array export #2'
        );
    }

    public function testDescription() {
        $this->assertSame(
            'Lorem ipsum sid dolor',
            $this->emConf->getDescription(),
            'get description from given array'
        );

        $this->emConf->setDescription('Lorem ipsum');

        $this->assertSame(
            'Lorem ipsum',
            $this->emConf->getDescription(),
            'description set in setDescription is available in getDescription'
        );

	    $emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'description',
            $emConfArray,
            'description set in setDescription is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['description'],
            'Lorem ipsum',
            'description set in setDescription is available in array export #2'
        );
    }

    public function testCategory() {
        $this->assertSame(
            'module',
            $this->emConf->getCategory(),
            'get description from given array'
        );

        $this->emConf->setCategory(EmConf::CATEGORY_EXAMPLE);

        $this->assertSame(
            EmConf::CATEGORY_EXAMPLE,
            $this->emConf->getCategory(),
            'category set in setCategory is available in getCategory'
        );

	    $emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'category',
            $emConfArray,
            'category set in setCategory is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['category'],
            EmConf::CATEGORY_EXAMPLE,
            'category set in setCategory is available in array export #2'
        );
    }

    public function testShy() {
        $this->assertSame(
            FALSE,
            $this->emConf->isShy(),
            'get shy from given array'
        );

        $this->emConf->setShy(TRUE);

        $this->assertSame(
            TRUE,
            $this->emConf->isShy(),
            'shy set in setShy is available in isShy'
        );

	    $emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'shy',
            $emConfArray,
            'shy set in setShy is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['shy'],
            1,
            'shy set in setShy is available in array export #2'
        );
    }

    public function testPriority() {
        $this->assertSame(
            EmConf::PRIORITY_DEFAULT,
            $this->emConf->getPriority(),
            'get priority from given array'
        );

        $this->emConf->setPriority(EmConf::PRIORITY_TOP);

        $this->assertSame(
            EmConf::PRIORITY_TOP,
            $this->emConf->getPriority(),
            'shy set in setPriority is available in getPriority'
        );

	    $emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'priority',
            $emConfArray,
            'priority set in setPriority is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['priority'],
            EmConf::PRIORITY_TOP,
            'priority set in setPriority is available in array export #2'
        );
    }

    public function testState() {
        $this->assertSame(
            EmConf::STATE_BETA,
            $this->emConf->getState(),
            'get state from given array'
        );

        $this->emConf->setState(EmConf::STATE_TEST);

        $this->assertSame(
            EmConf::STATE_TEST,
            $this->emConf->getState(),
            'shy set in setShy is available in isShy'
        );

	    $emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'state',
            $emConfArray,
            'state set in setState is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['state'],
            EmConf::STATE_TEST,
            'state set in setState is available in array export #2'
        );
    }

    public function testClearCacheOnLoad() {
        $this->assertSame(
            FALSE,
            $this->emConf->hasClearCacheOnLoad(),
            'get clearCacheOnLoad from given array'
        );

        $this->emConf->setClearCacheOnLoad(TRUE);

        $this->assertSame(
            TRUE,
            $this->emConf->hasClearCacheOnLoad(),
            'clearCacheOnLoad set in setClearCacheOnLoad is available in hasClearCacheOnLoad'
        );

	    $emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'clearcacheonload',
            $emConfArray,
            'clearCacheOnLoad set in setClearCacheOnLoad is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['clearcacheonload'],
            1,
            'clearCacheOnLoad set in setClearCacheOnLoad is available in array export #2'
        );
    }

    public function testAuthor() {
        $this->assertSame(
            'John Doe',
            $this->emConf->getAuthor(),
            'get author from given array'
        );

        $this->emConf->setAuthor('Jane Doe');

        $this->assertSame(
            'Jane Doe',
            $this->emConf->getAuthor(),
            'author set in setAuthor is available in getAuthor'
        );

	    $emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'author',
            $emConfArray,
            'author set in setAuthor is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['author'],
            'Jane Doe',
            'author set in setAuthor is available in array export #2'
        );
    }

    public function testAuthorEmail() {
        $this->assertSame(
            'doe@example.com',
            $this->emConf->getAuthorEmail(),
            'get author_email from given array'
        );

        $this->emConf->setAuthorEmail('john@example.com');

        $this->assertSame(
            'john@example.com',
            $this->emConf->getAuthorEmail(),
            'author_email set in setAuthorEmail is available in getAuthorEmail'
        );
		$emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'author_email',
	        $emConfArray,
            'author_email set in setAuthorEmail is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['author_email'],
            'john@example.com',
            'author_email set in setAuthorEmail is available in array export #2'
        );
    }

    public function testAuthorCompany() {
        $this->assertSame(
            'Foobar Corp.',
            $this->emConf->getAuthorCompany(),
            'get author_company from given array'
        );

        $this->emConf->setAuthorCompany('BazBar Ltd.');

        $this->assertSame(
            'BazBar Ltd.',
            $this->emConf->getAuthorCompany(),
            'author_company set in setAuthorCompany is available in getAuthorCompany'
        );

	    $emConfArray = $this->emConf->getConfigurationArray();
        $this->assertArrayHasKey(
            'author_company',
            $emConfArray,
            'author_company set in setAuthorCompany is available in array export #1'
        );
        $this->assertSame(
            $emConfArray['author_company'],
            'BazBar Ltd.',
            'author_company set in setAuthorCompany is available in array export #2'
        );
    }


}