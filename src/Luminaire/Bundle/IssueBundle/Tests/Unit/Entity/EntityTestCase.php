<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Unit\Entity;

use Luminaire\Bundle\IssueBundle\Tests\Unit\TestCase;

/**
 * Class EntityTestCase
 */
class EntityTestCase extends TestCase
{
    /**
     * @param $entity
     * @param $property
     * @param $value
     */
    protected function abstractTestGettersAndSetters($entity, $property, $value)
    {
        $getter = 'get' . ucfirst($property);
        $this->assertNull($entity->$getter());

        $setter = 'set' . ucfirst($property);
        $this->assertSame($entity, $entity->$setter($value));
        $this->assertEquals($entity->$getter(), $value);
    }
}
