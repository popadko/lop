<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Unit\Provider;

use Luminaire\Bundle\IssueBundle\Entity\Issue;
use Luminaire\Bundle\IssueBundle\Provider\IssueEntityNameProvider;
use Luminaire\Bundle\IssueBundle\Tests\Unit\TestCase;
use Oro\Bundle\EntityBundle\Provider\EntityNameProviderInterface;

/**
 * Class IssueEntityNameProviderTest
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class IssueEntityNameProviderTest extends TestCase
{
    /**
     * @var IssueEntityNameProvider
     */
    protected $issueEntityNameProvider;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->issueEntityNameProvider = new IssueEntityNameProvider();
    }

    /**
     * @dataProvider namesProvider
     *
     * @param $format
     * @param $locale
     * @param $entity
     * @param $return
     */
    public function testGetName($format, $locale, $entity, $return)
    {
        $this->assertEquals($return, $this->issueEntityNameProvider->getName($format, $locale, $entity));
    }

    /**
     * @return array
     */
    public function namesProvider()
    {
        return [
            [
                'format' => null,
                'locale' => null,
                'entity' => (new \stdClass()),
                'return' => false,
            ],
            [
                'format' => EntityNameProviderInterface::SHORT,
                'locale' => null,
                'entity' => (new Issue()),
                'return' => null,
            ],
            [
                'format' => 'test',
                'locale' => null,
                'entity' => (new Issue())->setSummary('Summary'),
                'return' => ' Summary',
            ],
        ];
    }

    /**
     * @dataProvider namesDQLProvider
     *
     * @param $format
     * @param $locale
     * @param $entity
     * @param $return
     */
    public function testGetNameDQL($format, $locale, $className, $alias, $return)
    {
        $this->assertEquals($return, $this->issueEntityNameProvider->getNameDQL($format, $locale, $className, $alias));
    }

    /**
     * @return array
     */
    public function namesDQLProvider()
    {
        return [
            [
                'format'    => null,
                'locale'    => null,
                'className' => '',
                'alias'     => null,
                'return'    => false,
            ],
            [
                'format'    => EntityNameProviderInterface::SHORT,
                'locale'    => null,
                'className' => 'Luminaire\Bundle\IssueBundle\Entity\Issue',
                'alias'     => 'i',
                'return'    => 'i.id',
            ],
            [
                'format'    => 'test',
                'locale'    => null,
                'className' => 'Luminaire\Bundle\IssueBundle\Entity\Issue',
                'alias'     => 'i',
                'return'    => 'CONCAT(i.id, \' \', i.summary)',
            ],
        ];
    }
}
