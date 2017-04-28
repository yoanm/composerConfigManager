<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AutoloadListNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Autoload;
use Yoanm\ComposerConfigManager\Domain\Model\AutoloadEntry;

class AutoloadListNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AutoloadListNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->normalizer = new AutoloadListNormalizer();
    }

    public function testNormalize()
    {
        $list = [];
        $type = 'type';
        $entryList = [];
        $namespace = 'namespace';
        $path = 'path';
        $namespace2 = 'namespace2';
        $path2 = 'path2';

        /** @var Autoload|ObjectProphecy $autoload */
        $autoload = $this->prophesize(Autoload::class);
        /** @var Autoload|ObjectProphecy $autoload2 */
        $autoload2 = $this->prophesize(Autoload::class);

        $list[] = $autoload->reveal();
        $list[] = $autoload2->reveal();

        $autoload->getType()
            ->willReturn($type)
            ->shouldBeCalled();
        $autoload->getNamespace()
            ->willReturn($namespace)
            ->shouldBeCalled();
        $autoload->getPath()
            ->willReturn($path)
            ->shouldBeCalled();
        $autoload2->getType()
            ->willReturn($type)
            ->shouldBeCalled();
        $autoload2->getNamespace()
            ->willReturn($namespace2)
            ->shouldBeCalled();
        $autoload2->getPath()
            ->willReturn($path2)
            ->shouldBeCalled();

        $expected = [
            $type => [
                $namespace => $path,
                $namespace2 => $path2,
            ]
        ];

        $this->assertSame(
            $expected,
            $this->normalizer->normalize($list)
        );
    }
}
