<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Infrastructure\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationFileDenormalizer as AppDenormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationFileNormalizer as AppNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\ComposerConfigManager\Infrastructure\Serializer\Normalizer\ConfigurationFileNormalizer;

class ConfigurationFileNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AppNormalizer|ObjectProphecy */
    private $appConfigurationFileNormalizer;
    /** @var AppDenormalizer|ObjectProphecy */
    private $appConfigurationFileDenormalizer;
    /** @var ConfigurationFileNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->appConfigurationFileNormalizer = $this->prophesize(AppNormalizer::class);
        $this->appConfigurationFileDenormalizer = $this->prophesize(AppDenormalizer::class);
        $this->normalizer = new ConfigurationFileNormalizer(
            $this->appConfigurationFileNormalizer->reveal(),
            $this->appConfigurationFileDenormalizer->reveal()
        );
    }

    public function testEncode()
    {
        $normalizedData = 'normalized_data';

        /** @var ConfigurationFile|ObjectProphecy $configurationFile */
        $configurationFile = $this->prophesize(ConfigurationFile::class);

        $this->appConfigurationFileNormalizer->normalize($configurationFile->reveal())
            ->willReturn($normalizedData)
            ->shouldBeCalled();

        $this->assertSame(
            $normalizedData,
            $this->normalizer->normalize($configurationFile->reveal())
        );
    }

    public function testDecode()
    {
        $normalizedData = ['normalized_data'];

        /** @var ConfigurationFile|ObjectProphecy $configurationFile */
        $configurationFile = $this->prophesize(ConfigurationFile::class);

        $this->appConfigurationFileDenormalizer->denormalize($normalizedData)
            ->willReturn($configurationFile->reveal())
            ->shouldBeCalled();

        $this->assertSame(
            $configurationFile->reveal(),
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
                'class' => ConfigurationFile::class,
                'expectedResult' => true
            ],
            'other' => [
                'class' => \stdClass::class,
                'expectedResult' => false
            ],
        ];
    }
}
