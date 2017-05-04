<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AutoloadListNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Autoload;

/**
 * @covers Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AutoloadListNormalizer
 */
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

    public function testDenormalize()
    {
        $type = 'type';
        $namespace = 'namespace';
        $path = 'path';
        $namespace2 = 'namespace2';
        $path2 = 'path2';
        $list = [
            $type => [
                $namespace => $path,
                $namespace2 => $path2,
            ],
        ];

        $denormalizedList = $this->normalizer->denormalize($list);

        $this->assertContainsOnlyInstancesOf(Autoload::class, $denormalizedList);
        $this->assertCount(2, $denormalizedList);

        $autoload = array_shift($denormalizedList);
        $this->assertSame($type, $autoload->getType());
        $this->assertSame($namespace, $autoload->getNamespace());
        $this->assertSame($path, $autoload->getPath());

        $autoload = array_shift($denormalizedList);
        $this->assertSame($type, $autoload->getType());
        $this->assertSame($namespace2, $autoload->getNamespace());
        $this->assertSame($path2, $autoload->getPath());
    }
}
