<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Updater;

use Yoanm\ComposerConfigManager\Application\Updater\PlainValueUpdater;

class PlainValueUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /** @var PlainValueUpdater */
    private $updater;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->updater = new PlainValueUpdater();
    }

    /**
     * @dataProvider getTestUpdateData
     *
     * @param string $newValue
     * @param string $baseValue
     * @param string $expected
     */
    public function testUpdate($newValue, $baseValue, $expected)
    {
        $this->assertSame(
            $expected,
            $this->updater->update($newValue, $baseValue)
        );
    }

    /**
     * @return array
     */
    public function getTestUpdateData()
    {
        return [
            'new value' => [
                'newValue' => 'OK',
                'baseValue' => 'KO',
                'expected' => 'OK',
            ],
            'new value is null' => [
                'newValue' => null,
                'baseValue' => 'KO',
                'expected' => 'KO',
            ],
        ];
    }
}
