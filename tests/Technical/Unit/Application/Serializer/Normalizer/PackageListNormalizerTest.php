<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\PackageListNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Package;

/**
 * @covers Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\PackageListNormalizer
 */
class PackageListNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var PackageListNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->normalizer = new PackageListNormalizer();
    }

    public function testNormalize()
    {
        $list = [];
        $name = 'name';
        $versionConstraint = 'versionConstraint';
        $name2 = 'name2';
        $versionConstraint2 = 'versionConstraint2';

        /** @var Package|ObjectProphecy $package */
        $package = $this->prophesize(Package::class);
        /** @var Package|ObjectProphecy $package2 */
        $package2 = $this->prophesize(Package::class);

        $list[] = $package->reveal();
        $list[] = $package2->reveal();

        $package->getName()
            ->willReturn($name)
            ->shouldBeCalled();
        $package->getVersionConstraint()
            ->willReturn($versionConstraint)
            ->shouldBeCalled();
        $package2->getName()
            ->willReturn($name2)
            ->shouldBeCalled();
        $package2->getVersionConstraint()
            ->willReturn($versionConstraint2)
            ->shouldBeCalled();

        $expected = [
            $name => $versionConstraint,
            $name2 => $versionConstraint2,
        ];

        $this->assertSame(
            $expected,
            $this->normalizer->normalize($list)
        );
    }

    public function testDenormalize()
    {
        $name = 'name';
        $versionConstraint = 'versionConstraint';
        $name2 = 'name2';
        $versionConstraint2 = 'versionConstraint2';
        $list = [
            $name => $versionConstraint,
            $name2 => $versionConstraint2,
        ];

        $denormalizedList = $this->normalizer->denormalize($list);

        $this->assertContainsOnlyInstancesOf(Package::class, $denormalizedList);
        $this->assertCount(count($list), $denormalizedList);

        $package = array_shift($denormalizedList);
        $this->assertSame($name, $package->getName());
        $this->assertSame($versionConstraint, $package->getVersionConstraint());

        $package = array_shift($denormalizedList);
        $this->assertSame($name2, $package->getName());
        $this->assertSame($versionConstraint2, $package->getVersionConstraint());
    }
}
