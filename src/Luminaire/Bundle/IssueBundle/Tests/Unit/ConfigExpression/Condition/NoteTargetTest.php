<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Unit\ConfigExpression\Condition;

use Luminaire\Bundle\IssueBundle\ConfigExpression\Condition\NoteTarget;
use Luminaire\Bundle\IssueBundle\Tests\Unit\TestCase;

/**
 * Class NoteTargetTest
 *
 * * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class NoteTargetTest extends TestCase
{
    /**
     * @var NoteTarget
     */
    protected $noteTarget;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->noteTarget = new NoteTarget();
    }

    /**
     *
     */
    public function testGetName()
    {
        $this->assertEquals('note_target', $this->noteTarget->getName());
    }

    /**
     * @expectedException \Oro\Component\ConfigExpression\Exception\InvalidArgumentException
     * @expectedExceptionMessage Options must have 2 elements, but 0 given.
     */
    public function testInitializeEmptyArgs()
    {
        $this->noteTarget->initialize([]);
    }

    /**
     * @expectedException \Oro\Component\ConfigExpression\Exception\InvalidArgumentException
     * @expectedExceptionMessage Options must have 2 elements, but 1 given.
     */
    public function testInitializeOneArg()
    {
        $this->noteTarget->initialize([1]);
    }

    /**
     * @expectedException \Oro\Component\ConfigExpression\Exception\InvalidArgumentException
     * @expectedExceptionMessage Option "note" is required.
     */
    public function testInitializeEmptyNoteIndex()
    {
        $this->noteTarget->initialize(['test' => 1, 'test1' => 2]);
    }

    /**
     * @expectedException \Oro\Component\ConfigExpression\Exception\InvalidArgumentException
     * @expectedExceptionMessage Option "note" is required.
     */
    public function testInitializeEmptyNote()
    {
        $this->noteTarget->initialize([2 => 1, 3 => 2]);
    }

    /**
     * @expectedException \Oro\Component\ConfigExpression\Exception\InvalidArgumentException
     * @expectedExceptionMessage Option "targetClass" is required.
     */
    public function testInitializeEmptyTargetClassIndex()
    {
        $this->noteTarget->initialize(['note' => 1, 'test1' => 2]);
    }

    /**
     * @expectedException \Oro\Component\ConfigExpression\Exception\InvalidArgumentException
     * @expectedExceptionMessage Option "targetClass" is required.
     */
    public function testInitializeEmptyTargetClass()
    {
        $this->noteTarget->initialize([0 => 1, 2 => 2]);
    }

    /**
     *
     */
    public function testInitialize()
    {
        $this->noteTarget->initialize([0 => 1, 1 => 2]);
        $this->noteTarget->initialize(['note' => 1, 'targetClass' => 2]);
    }

    /**
     * @param $options
     * @param $message
     * @param $expected
     *
     * @dataProvider toArrayDataProvider
     */
    public function testToArray($options, $message, $expected)
    {
        $this->noteTarget->initialize($options);
        if ($message !== null) {
            $this->noteTarget->setMessage($message);
        }
        $actual = $this->noteTarget->toArray();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function toArrayDataProvider()
    {
        return [
            [
                'options'  => ['note', 'targetClass'],
                'message'  => null,
                'expected' => [
                    '@note_target' => [
                        'parameters' => [
                            'note',
                            'targetClass'
                        ]
                    ]
                ]
            ],
            [
                'options'  => ['note', 'targetClass'],
                'message'  => 'Test',
                'expected' => [
                    '@note_target' => [
                        'message'    => 'Test',
                        'parameters' => [
                            'note',
                            'targetClass'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @param $options
     * @param $message
     * @param $expected
     *
     * @dataProvider compileDataProvider
     */
    public function testCompile($options, $message, $expected)
    {
        $this->noteTarget->initialize($options);
        if ($message !== null) {
            $this->noteTarget->setMessage($message);
        }
        $actual = $this->noteTarget->compile('$factory');
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function compileDataProvider()
    {
        return [
            [
                'options'  => ['note', 'targetClass'],
                'message'  => null,
                'expected' => '$factory->create(\'note_target\', [\'note\', \'targetClass\'])'
            ],
            [
                'options'  => ['note', 'targetClass'],
                'message'  => 'Test',
                'expected' => '$factory->create(\'note_target\', [\'note\', \'targetClass\'])->setMessage(\'Test\')'
            ]
        ];
    }
}
