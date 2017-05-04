<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationDenormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationFileDenormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

/**
 * @covers Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationFileDenormalizer
 */
class ConfigurationFileDenormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigurationDenormalizer|ObjectProphecy */
    private $configurationDenormalizer;
    /** @var ConfigurationFileDenormalizer */
    private $denormalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->configurationDenormalizer = $this->prophesize(ConfigurationDenormalizer::class);
        $this->denormalizer = new ConfigurationFileDenormalizer(
            $this->configurationDenormalizer->reveal()
        );
    }

    public function testDenormalize()
    {
        $configuration = ['a' => 'configuration', 'c' => 'c', 'b' => 'b'];
        /** @var Configuration|ObjectProphecy $denormalizedConfiguration */
        $denormalizedConfiguration = $this->prophesize(Configuration::class);

        $this->configurationDenormalizer->denormalize($configuration)
            ->willReturn($denormalizedConfiguration->reveal())
            ->shouldBeCalled();

        $configurationFile = $this->denormalizer->denormalize($configuration);
        $this->assertInstanceOf(ConfigurationFile::class, $configurationFile);
        $this->assertSame($denormalizedConfiguration->reveal(), $configurationFile->getConfiguration());
        $this->assertSame(['a', 'c', 'b'], $configurationFile->getKeyList());
    }
}
