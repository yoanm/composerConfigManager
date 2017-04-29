<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Infrastructure\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationDenormalizer as AppConfigDenormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationNormalizer as AppConfigNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Infrastructure\Serializer\Normalizer\ConfigurationNormalizer;

class ConfigurationNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AppConfigNormalizer|ObjectProphecy */
    private $appConfigurationNormalizer;
    /** @var AppConfigDenormalizer|ObjectProphecy */
    private $appConfigurationDenormalizer;
    /** @var ConfigurationNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->appConfigurationNormalizer = $this->prophesize(AppConfigNormalizer::class);
        $this->appConfigurationDenormalizer = $this->prophesize(AppConfigDenormalizer::class);
        $this->normalizer = new ConfigurationNormalizer(
            $this->appConfigurationNormalizer->reveal(),
            $this->appConfigurationDenormalizer->reveal()
        );
    }

    public function testEncode()
    {
        $normalizedData = 'normalized_data';

        $configuration = $this->prophesize(Configuration::class);

        $this->appConfigurationNormalizer->normalize($configuration->reveal())
            ->willReturn($normalizedData)
            ->shouldBeCalled();

        $this->assertSame(
            $normalizedData,
            $this->normalizer->normalize($configuration->reveal())
        );
    }

    public function testDecode()
    {
        $normalizedData = ['normalized_data'];

        $configuration = $this->prophesize(Configuration::class);

        $this->appConfigurationDenormalizer->denormalize($normalizedData)
            ->willReturn($configuration->reveal())
            ->shouldBeCalled();

        $this->assertSame(
            $configuration->reveal(),
            $this->normalizer->denormalize($normalizedData, Configuration::class)
        );
    }

    /**
     * @dataProvider getTestSupportsClassData
     *
     * @param string $class
     * @param bool   $expectedResult
     */
    public function testSupportsNormalization($class, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            $this->normalizer->supportsNormalization($this->prophesize($class)->reveal())
        );
    }

    /**
     * @dataProvider getTestSupportsClassData
     *
     * @param string $class
     * @param bool   $expectedResult
     */
    public function testSupportsDenormalization($class, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            $this->normalizer->supportsDenormalization([], $class)
        );
    }

    /**
     * @return array
     */
    public function getTestSupportsClassData()
    {
        return [
            'Configuration class' => [
                'class' => Configuration::class,
                'expectedResult' => true
            ],
            'other' => [
                'class' => \stdClass::class,
                'expectedResult' => false
            ],
        ];
    }
}
