<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationFileNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationFileNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigurationNormalizer|ObjectProphecy */
    private $configurationNormalizer;
    /** @var ConfigurationFileNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->configurationNormalizer = $this->prophesize(ConfigurationNormalizer::class);
        $this->normalizer = new ConfigurationFileNormalizer(
            $this->configurationNormalizer->reveal()
        );
    }

    public function testNormalize()
    {
        $normalizedConfiguration = [
            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
            'd' => 'd',
            'e' => 'e',
            'f' => 'f',
        ];
        $keyList = ['a', 'c', 'e', 'd'];
        $expected = [
            'a' => 'a',
            'c' => 'c',
            'e' => 'e',
            'd' => 'd',
            'b' => 'b',
            'f' => 'f',
        ];

        /** @var ConfigurationFile|ObjectProphecy $configurationFile */
        $configurationFile = $this->prophesize(ConfigurationFile::class);
        /** @var Configuration|ObjectProphecy $configuration */
        $configuration = $this->prophesize(Configuration::class);

        $configurationFile->getConfiguration()
            ->willReturn($configuration->reveal())
            ->shouldBeCalled();
        $configurationFile->getKeyList()
            ->willReturn($keyList)
            ->shouldBeCalled();
        $this->configurationNormalizer->normalize($configuration->reveal())
            ->willReturn($normalizedConfiguration)
            ->shouldBeCalled();

        $this->assertSame(
            $expected,
            $this->normalizer->normalize($configurationFile->reveal())
        );
    }
}
