<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Updater;

use Yoanm\ComposerConfigManager\Application\Updater\KeywordListUpdater;

/**
 * @covers Yoanm\ComposerConfigManager\Application\Updater\KeywordListUpdater
 */
class KeywordListUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /** @var KeywordListUpdater */
    private $updater;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->updater = new KeywordListUpdater();
    }

    public function testUpdate()
    {
        $newEntityList = ['Keyword'];
        $oldEntityList = [];

        $list = $this->updater->update($newEntityList, $oldEntityList);

        $this->assertCount(1, $list);
        $this->assertSame($newEntityList, $list);
    }

    public function testUpdateWithOldValues()
    {
        $newEntityList = ['new'];
        $oldEntityList = ['old'];

        $list = $this->updater->update($newEntityList, $oldEntityList);

        $this->assertCount(2, $list);
        // Assert Old entities are before new ones
        $this->assertSame($oldEntityList[0], array_shift($list));
        $this->assertSame($newEntityList[0], array_shift($list));
    }

    public function testUpdateRemoveDuplicatedValues()
    {
        $newEntityList = ['k1'];
        $oldEntityList = ['k1', 'k2'];

        $list = $this->updater->update($newEntityList, $oldEntityList);

        $this->assertCount(2, $list);
        // Assert Old entities are before new ones
        $this->assertSame($oldEntityList[0], array_shift($list));
        $this->assertSame($oldEntityList[1], array_shift($list));
    }
}
