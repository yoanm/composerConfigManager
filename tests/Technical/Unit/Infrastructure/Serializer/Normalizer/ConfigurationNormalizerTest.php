<?php
namespace Technical\Unit\Yoanm\InitRepositoryWithComposer\Infrastructure\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer\ConfigurationNormalizer as AppConfigNormalizer;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;
use Yoanm\InitRepositoryWithComposer\Infrastructure\Serializer\Normalizer\ConfigurationNormalizer;

class ConfigurationNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AppConfigNormalizer|ObjectProphecy */
    private $appConfigurationNormalizer;
    /** @var ConfigurationNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->appConfigurationNormalizer = $this->prophesize(AppConfigNormalizer::class);
        $this->normalizer = new ConfigurationNormalizer(
            $this->appConfigurationNormalizer->reveal()
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

    /**
     * @dataProvider getTestSupportsEncodingData
     *
     * @param string $class
     * @param bool   $expectedResult
     */
    public function testSupportsEncoding($class, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            $this->normalizer->supportsNormalization($this->prophesize($class)->reveal())
        );
    }

    /**
     * @return array
     */
    public function getTestSupportsEncodingData()
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
