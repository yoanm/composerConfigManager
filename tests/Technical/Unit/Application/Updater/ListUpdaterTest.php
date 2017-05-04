<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Updater;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Updater\ListUpdater;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationItemInterface;

class ListUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ListUpdater */
    private $updater;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->updater = new ListUpdater();
    }

    public function testUpdate()
    {
        $itemId = 'id';
        $newEntityList = [];
        $oldEntityList = [];

        /** @var ConfigurationItemInterface|ObjectProphecy $entity */
        $entity = $this->prophesize(ConfigurationItemInterface::class);

        $newEntityList[] = $entity->reveal();

        $entity->getItemId()
            ->willReturn($itemId)
            ->shouldBeCalled();

        $list = $this->updater->update($newEntityList, $oldEntityList);

        $this->assertCount(1, $list);
        $this->assertContainsOnlyInstancesOf(ConfigurationItemInterface::class, $list);
        $this->assertSame($entity->reveal(), array_shift($list));
    }

    public function testUpdateWithOldValues()
    {
        $itemId = 'id';
        $oldItemId = 'id0';
        $newEntityList = [];
        $oldEntityList = [];

        /** @var ConfigurationItemInterface|ObjectProphecy $newEntity */
        $newEntity = $this->prophesize(ConfigurationItemInterface::class);
        /** @var ConfigurationItemInterface|ObjectProphecy $oldEntity */
        $oldEntity = $this->prophesize(ConfigurationItemInterface::class);

        $newEntityList[] = $newEntity->reveal();
        $oldEntityList[] = $oldEntity->reveal();

        $newEntity->getItemId()
            ->willReturn($itemId)
            ->shouldBeCalled();
        $oldEntity->getItemId()
            ->willReturn($oldItemId)
            ->shouldBeCalled();

        $list = $this->updater->update($newEntityList, $oldEntityList);

        $this->assertCount(2, $list);
        $this->assertContainsOnlyInstancesOf(ConfigurationItemInterface::class, $list);
        // Assert Old entities are before new ones
        $this->assertSame($oldEntity->reveal(), array_shift($list));
        $this->assertSame($newEntity->reveal(), array_shift($list));
    }

    public function testUpdateWithOldValuesToUpdateAndNewValues()
    {
        $itemId = 'id';
        $itemId2 = 'id2';
        $oldItemId = 'id_old';
        $newEntityList = [];
        $oldEntityList = [];

        /** @var ConfigurationItemInterface|ObjectProphecy $newEntity1 */
        $newEntity1 = $this->prophesize(ConfigurationItemInterface::class);
        /** @var ConfigurationItemInterface|ObjectProphecy $newEntity2 */
        $newEntity2 = $this->prophesize(ConfigurationItemInterface::class);
        /** @var ConfigurationItemInterface|ObjectProphecy $oldEntity1 */
        $oldEntity1 = $this->prophesize(ConfigurationItemInterface::class);
        /** @var ConfigurationItemInterface|ObjectProphecy $oldEntity2 */
        $oldEntity2= $this->prophesize(ConfigurationItemInterface::class);

        $newEntityList[] = $newEntity1->reveal();
        $newEntityList[] = $newEntity2->reveal();
        $oldEntityList[] = $oldEntity1->reveal();
        $oldEntityList[] = $oldEntity2->reveal();

        $newEntity1->getItemId()
            ->willReturn($itemId)
            ->shouldBeCalled();
        $newEntity2->getItemId()
            ->willReturn($itemId2)
            ->shouldBeCalled();
        $oldEntity1->getItemId()
            ->willReturn($itemId)
            ->shouldBeCalled();
        $oldEntity2->getItemId()
            ->willReturn($oldItemId)
            ->shouldBeCalled();

        $list = $this->updater->update($newEntityList, $oldEntityList);

        // New item should have been merged with the related old one
        $this->assertCount(3, $list);
        $this->assertContainsOnlyInstancesOf(ConfigurationItemInterface::class, $list);
        // Assert Old (or updated) entities are before new ones
        $this->assertSame($newEntity1->reveal(), array_shift($list));
        $this->assertSame($oldEntity2->reveal(), array_shift($list));
        $this->assertSame($newEntity2->reveal(), array_shift($list));
    }

    public function testUpdateRaw()
    {
        $itemId = 'id';
        $newPropertyList = [
            'a' => 'b'
        ];
        $oldPropertyList = [];

        $list = $this->updater->updateRaw($newPropertyList, $oldPropertyList);

        $this->assertCount(1, $list);
        $this->assertSame($newPropertyList, $list);
    }

    public function testUpdateRawWithOldValues()
    {
        $newPropertyList = [
            'c' => 'd',
        ];
        $oldPropertyList = [
            'a' => 'b',
        ];

        // Assert Old entities are before new ones
        $this->assertSame(
            [
                'a' => 'b',
                'c' => 'd',
            ],
            $this->updater->updateRaw($newPropertyList, $oldPropertyList)
        );
    }

    public function testUpdateRawWithOldValuesToUpdateAndNewValues()
    {
        $newPropertyList = [
            'c' => [
                'd' => [
                    'e' => 'f2',
                    'i' => 'j',
                ]
            ],
            'k' => ['l', 'm'],
        ];
        $oldPropertyList = [
            'a' => 'b',
            'c' => [
                'd' => [
                    'e' => 'f',
                    'g' => 'h'
                ],
            ],
        ];


        $this->assertSame(
            [
                'a' => 'b',
                'c' => [
                    'd' => [
                        'e' => 'f2',
                        'g' => 'h',
                        'i' => 'j',
                    ]
                ],
                'k' => ['l', 'm'],
            ],
            $this->updater->updateRaw($newPropertyList, $oldPropertyList)
        );
    }
}
