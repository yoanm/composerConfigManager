<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Updater;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Updater\AuthorListUpdater;
use Yoanm\ComposerConfigManager\Domain\Model\Author;

class AuthorListUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /** @var AuthorListUpdater */
    private $updater;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->updater = new AuthorListUpdater();
    }

    public function testUpdate()
    {
        $itemId = 'id';
        $newEntityList = [];
        $oldEntityList = [];

        /** @var Author|ObjectProphecy $entity */
        $entity = $this->prophesize(Author::class);

        $newEntityList[] = $entity->reveal();

        $entity->getItemId()
            ->willReturn($itemId)
            ->shouldBeCalled();

        $list = $this->updater->update($newEntityList, $oldEntityList);

        $this->assertCount(1, $list);
        $this->assertContainsOnlyInstancesOf(Author::class, $list);
        $this->assertSame($entity->reveal(), array_shift($list));
    }

    public function testUpdateWithOldValues()
    {
        $itemId = 'id';
        $oldItemId = 'id0';
        $newEntityList = [];
        $oldEntityList = [];

        /** @var Author|ObjectProphecy $newEntity */
        $newEntity = $this->prophesize(Author::class);
        /** @var Author|ObjectProphecy $oldEntity */
        $oldEntity = $this->prophesize(Author::class);

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
        $this->assertContainsOnlyInstancesOf(Author::class, $list);
        // Assert Old entities are before new ones
        $this->assertSame($oldEntity->reveal(), array_shift($list));
        $this->assertSame($newEntity->reveal(), array_shift($list));
    }

    public function testUpdateWithOldValuesToUpdateAndNewValues()
    {
        $name = 'name';
        $email = 'email';
        $role = 'role';
        $itemId = 'id';
        $itemId2 = 'id2';
        $oldItemId = 'id_old';
        $newEntityList = [];
        $oldEntityList = [];

        /** @var Author|ObjectProphecy $newEntity1 */
        $newEntity1 = $this->prophesize(Author::class);
        /** @var Author|ObjectProphecy $newEntity2 */
        $newEntity2 = $this->prophesize(Author::class);
        /** @var Author|ObjectProphecy $oldEntity1 */
        $oldEntity1 = $this->prophesize(Author::class);
        /** @var Author|ObjectProphecy $oldEntity2 */
        $oldEntity2= $this->prophesize(Author::class);

        $newEntityList[] = $newEntity1->reveal();
        $newEntityList[] = $newEntity2->reveal();
        $oldEntityList[] = $oldEntity1->reveal();
        $oldEntityList[] = $oldEntity2->reveal();

        $newEntity1->getItemId()
            ->willReturn($itemId)
            ->shouldBeCalled();
        $newEntity1->getName()
            ->willReturn($name)
            ->shouldBeCalled();
        $newEntity1->getEmail()
            ->willReturn($email)
            ->shouldBeCalled();
        $newEntity1->getRole()
            ->willReturn($role)
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
        $this->assertContainsOnlyInstancesOf(Author::class, $list);
        $updatedAuthor = array_shift($list);
        $this->assertSame($name, $updatedAuthor->getName());
        $this->assertSame($email, $updatedAuthor->getEmail());
        $this->assertSame($role, $updatedAuthor->getRole());
        // Assert Old (or updated) entities are before new ones
        $this->assertSame($oldEntity2->reveal(), array_shift($list));
        $this->assertSame($newEntity2->reveal(), array_shift($list));
    }
}
