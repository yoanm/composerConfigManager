<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\PackageListNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Package;

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
}
