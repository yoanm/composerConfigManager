<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\SuggestedPackageListNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\SuggestedPackage;

/**
 * @covers Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\SuggestedPackageListNormalizer
 */
class SuggestedPackageListNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var SuggestedPackageListNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->normalizer = new SuggestedPackageListNormalizer();
    }

    public function testNormalize()
    {
        $list = [];
        $name = 'name';
        $description = 'description';

        /** @var SuggestedPackage|ObjectProphecy $suggestedPackage */
        $suggestedPackage = $this->prophesize(SuggestedPackage::class);

        $list[] = $suggestedPackage->reveal();

        $suggestedPackage->getName()
            ->willReturn($name)
            ->shouldBeCalled();

        $suggestedPackage->getDescription()
            ->willReturn($description)
            ->shouldBeCalled();

        $expected = [
            $name => $description
        ];

        $this->assertSame(
            $expected,
            $this->normalizer->normalize($list)
        );
    }

    public function testDenormalize()
    {
        $name = 'name';
        $description = 'description';

        $list = [
            $name => $description
        ];

        $denormalizedList = $this->normalizer->denormalize($list);

        $this->assertContainsOnlyInstancesOf(SuggestedPackage::class, $denormalizedList);
        $this->assertCount(count($list), $denormalizedList);

        $suggestedPackage = array_shift($denormalizedList);
        $this->assertSame($name, $suggestedPackage->getName());
        $this->assertSame($description, $suggestedPackage->getDescription());
    }
}
