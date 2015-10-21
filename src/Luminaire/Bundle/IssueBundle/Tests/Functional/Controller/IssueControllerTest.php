<?php

namespace OroB2B\Bundle\OrderBundle\Tests\Functional\Controller;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

use Luminaire\Bundle\IssueBundle\Form\Type\IssueType as IssueFormType;
use Luminaire\Bundle\IssueBundle\Entity\IssuePriority;
use Luminaire\Bundle\IssueBundle\Entity\IssueStatus;
use Luminaire\Bundle\IssueBundle\Entity\IssueResolution;
use Luminaire\Bundle\IssueBundle\Entity\IssueType;
use Luminaire\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadIssueData;
use Luminaire\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadIssueUsersData;
use Luminaire\Bundle\IssueBundle\Tests\Functional\TestCase;

/**
 * @dbIsolation
 */
class IssueControllerTest extends TestCase
{
    const ISSUE_SUMMARY = 'new issue';
    const ISSUE_SUMMARY_UPDATED = 'summary updated';

    /**
     *
     */
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->loadFixtures([
            'Luminaire\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadIssueData',
            'Luminaire\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadIssueUsersData',
        ]);
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', $this->getUrl('luminaire_issue_index'));
        $result  = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertEquals('Issues', $crawler->filter('h1.oro-subtitle')->html());
    }

    /**
     * @return int
     */
    public function testCreate()
    {
        $crawler  = $this->client->request('GET', $this->getUrl('luminaire_issue_create'));
        $response = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($response, 200);

        /** @var Form $form */
        $form = $crawler->selectButton('Save')->form();

        $issueData = [
            'summary'     => self::ISSUE_SUMMARY,
            'description' => 'description',
            'reporter'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
            'assignee'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
            'priority'    => IssuePriority::PRIORITY_BLOCKER,
            'type'        => IssueType::TYPE_TASK,
            'status'      => IssueStatus::STATUS_OPEN,
        ];

        $submittedData = [
            'input_action'      => 'save_and_stay',
            IssueFormType::NAME => array_merge($issueData, [
                '_token' => $form[IssueFormType::NAME . '[_token]']->getValue(),
            ]),
        ];

        $this->client->followRedirects(true);

        // Submit form
        $response = $this->client->getResponse();
        $crawler  = $this->client->request($form->getMethod(), $form->getUri(), $submittedData);

        $this->assertHtmlResponseStatusCodeEquals($response, 200);

        $actualIssueData = $this->getActualIssueData($crawler);

        $this->assertEquals($issueData, $actualIssueData);

        $response = $this->client->requestGrid(
            'issues-grid',
            [
                'issues-grid[_filter][summary][value]' => self::ISSUE_SUMMARY
            ]
        );

        $response = $this->getJsonResponseContent($response, 200);
        $this->assertCount(1, $response['data']);
        $response = reset($response['data']);

        return $response['id'];
    }

    /**
     * @depends testCreate
     * @param int $id
     */
    public function testUpdate($id)
    {
        $this->assertEquals('/issue/update/' . $id, $this->getUrl('luminaire_issue_update', ['id' => $id]));
        $crawler = $this->client->request('GET', $this->getUrl('luminaire_issue_update', ['id' => $id]));

        $response = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($response, 200);

        /** @var Form $form */
        $form = $crawler->selectButton('Save and Close')->form();

        $issueData = [
            'summary'     => self::ISSUE_SUMMARY_UPDATED,
            'description' => 'new description',
            'reporter'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_2)->getId(),
            'assignee'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_2)->getId(),
            'priority'    => IssuePriority::PRIORITY_MAJOR,
            'type'        => IssueType::TYPE_TASK,
            'status'      => IssueStatus::STATUS_REOPENED,
        ];

        $submittedData = [
            'input_action'      => 'save_and_stay',
            IssueFormType::NAME => array_merge($issueData, [
                '_token' => $form[IssueFormType::NAME . '[_token]']->getValue(),
            ]),
        ];

        $this->client->followRedirects(true);

        // Submit form
        $result = $this->client->getResponse();
        $this->client->request($form->getMethod(), $form->getUri(), $submittedData);

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $crawler = $this->client->request('GET', $this->getUrl('luminaire_issue_update', ['id' => $id]));

        $actualIssueData = $this->getActualIssueData($crawler);

        $this->assertEquals($issueData, $actualIssueData);
    }

    /**
     * @depends testCreate
     *
     * @param int $id
     */
    public function testView($id)
    {
        $crawler = $this->client->request('GET', $this->getUrl('luminaire_issue_show', ['id' => $id]));

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $html = $crawler->html();
        $this->assertContains(self::ISSUE_SUMMARY_UPDATED, $html);
    }

    public function testCreateBlank()
    {
        $crawler  = $this->client->request('GET', $this->getUrl('luminaire_issue_create'));
        $response = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($response, 200);

        /** @var Form $form */
        $form = $crawler->selectButton('Save')->form();

        $submittedData = [
            'input_action'      => 'save_and_stay',
            IssueFormType::NAME => [
                '_token' => $form[IssueFormType::NAME . '[_token]']->getValue(),
            ],
        ];

        $this->client->followRedirects(true);

        // Submit form
        $response = $this->client->getResponse();
        $crawler  = $this->client->request($form->getMethod(), $form->getUri(), $submittedData);

        $this->assertHtmlResponseStatusCodeEquals($response, 200);

        $errors = $crawler->filter('.validation-failed');
        $this->assertCount(6, $errors);
        $errors->each(function (Crawler $error) {
            $this->assertEquals('This value should not be blank.', $error->html());
        });
    }

    public function testCreateEmptyResolution()
    {
        foreach ($this->dataProviderForCustomConstraints() as $data) {
            list($issueData, $errorMessage) = $data;
            $this->createEmptyResolutionProcess($issueData, $errorMessage);
        }

    }

    protected function createEmptyResolutionProcess($issueData, $errorMessage)
    {
        $crawler  = $this->client->request('GET', $this->getUrl('luminaire_issue_create'));
        $response = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($response, 200);

        /** @var Form $form */
        $form = $crawler->selectButton('Save')->form();

        $submittedData = [
            'input_action'      => 'save_and_stay',
            IssueFormType::NAME => array_merge($issueData, [
                '_token' => $form[IssueFormType::NAME . '[_token]']->getValue(),
            ]),
        ];

        $this->client->followRedirects(true);

        // Submit form
        $response = $this->client->getResponse();
        $crawler  = $this->client->request($form->getMethod(), $form->getUri(), $submittedData);

        $this->assertHtmlResponseStatusCodeEquals($response, 200);

        $errors = $crawler->filter('.validation-failed');
        $this->assertCount(1, $errors);
        $this->assertEquals($errorMessage, $errors->first()->html());
    }

    /**
     * TODO: @dataProvider annotation has problems with getReference method
     */
    public function dataProviderForCustomConstraints()
    {
        return [
            [
                [
                    'summary'     => self::ISSUE_SUMMARY,
                    'description' => 'description',
                    'reporter'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'assignee'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'priority'    => IssuePriority::PRIORITY_BLOCKER,
                    'type'        => IssueType::TYPE_TASK,
                    'status'      => IssueStatus::STATUS_RESOLVED,
                ],
                'Resolution must be set for Resolved issue.',
            ],
            [
                [
                    'summary'     => self::ISSUE_SUMMARY,
                    'description' => 'description',
                    'reporter'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'assignee'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'priority'    => IssuePriority::PRIORITY_BLOCKER,
                    'type'        => IssueType::TYPE_TASK,
                    'status'      => IssueStatus::STATUS_OPEN,
                    'resolution'  => IssueResolution::RESOLUTION_FIXED,
                ],
                'Resolution can be set only for Resolved issue.',
            ],
            [
                [
                    'summary'     => self::ISSUE_SUMMARY,
                    'description' => 'description',
                    'reporter'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'assignee'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'priority'    => IssuePriority::PRIORITY_BLOCKER,
                    'type'        => IssueType::TYPE_SUBTASK,
                    'status'      => IssueStatus::STATUS_OPEN,
                ],
                'Parent issue must be set for Subtask issue.',
            ],
            [
                [
                    'summary'     => self::ISSUE_SUMMARY,
                    'description' => 'description',
                    'reporter'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'assignee'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'priority'    => IssuePriority::PRIORITY_BLOCKER,
                    'type'        => IssueType::TYPE_SUBTASK,
                    'status'      => IssueStatus::STATUS_OPEN,
                    'parent'      => $this->getReference(LoadIssueData::ISSUE_BUG)->getId(),
                ],
                'This value is not valid.',
            ],
            [
                [
                    'summary'     => self::ISSUE_SUMMARY,
                    'description' => 'description',
                    'reporter'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'assignee'    => $this->getReference(LoadIssueUsersData::ISSUE_USER_1)->getId(),
                    'priority'    => IssuePriority::PRIORITY_BLOCKER,
                    'type'        => IssueType::TYPE_TASK,
                    'status'      => IssueStatus::STATUS_OPEN,
                    'parent'      => $this->getReference(LoadIssueData::ISSUE_STORY)->getId(),
                ],
                'Parent issue can be set only for Subtask issue.',
            ]
        ];
    }

    /**
     * @param Crawler $crawler
     * @param int $count
     * @return array
     */
    protected function getActualIssueData(Crawler $crawler)
    {
        return [
            'summary'     => $crawler->filter(sprintf('input[name="%s[%s]"]', IssueFormType::NAME, 'summary'))
                ->extract('value')[0],
            'description' => $crawler->filter(sprintf('textarea[name="%s[%s]"]', IssueFormType::NAME, 'description'))
                ->html(),
            'reporter'    => $crawler
                ->filter(sprintf('select[name="%s[%s]"] :selected', IssueFormType::NAME, 'reporter'))
                ->extract('value')[0],
            'assignee'    => $crawler
                ->filter(sprintf('select[name="%s[%s]"] :selected', IssueFormType::NAME, 'reporter'))
                ->extract('value')[0],
            'priority'    => $crawler
                ->filter(sprintf('select[name="%s[%s]"] :selected', IssueFormType::NAME, 'priority'))
                ->extract('value')[0],
            'type'        => $crawler->filter(sprintf('select[name="%s[%s]"] :selected', IssueFormType::NAME, 'type'))
                ->extract('value')[0],
            'status'      => $crawler->filter(sprintf('select[name="%s[%s]"] :selected', IssueFormType::NAME, 'status'))
                ->extract('value')[0],
        ];
    }
}
