<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AuthorListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AutoloadListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationDenormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\PackageListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ScriptListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\SuggestedPackageListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\SupportListNormalizer;

/**
 * Class ConfigurationDenormalizer
 */
class ConfigurationDenormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AuthorListNormalizer|ObjectProphecy */
    private $authorListNormalizer;
    /** @var PackageListNormalizer|ObjectProphecy */
    private $packageListNormalizer;
    /** @var SuggestedPackageListNormalizer|ObjectProphecy */
    private $suggestedPackageListNormalizer;
    /** @var SupportListNormalizer|ObjectProphecy */
    private $supportListNormalizer;
    /** @var AutoloadListNormalizer|ObjectProphecy */
    private $autoloadListNormalizer;
    /** @var ScriptListNormalizer|ObjectProphecy */
    private $scriptListNormalizer;
    /** @var ConfigurationDenormalizer */
    private $denormalizer;

    public function setUp()
    {
        $this->authorListNormalizer = $this->prophesize(AuthorListNormalizer::class);
        $this->packageListNormalizer = $this->prophesize(PackageListNormalizer::class);
        $this->suggestedPackageListNormalizer = $this->prophesize(SuggestedPackageListNormalizer::class);
        $this->supportListNormalizer = $this->prophesize(SupportListNormalizer::class);
        $this->autoloadListNormalizer = $this->prophesize(AutoloadListNormalizer::class);
        $this->scriptListNormalizer = $this->prophesize(ScriptListNormalizer::class);

        $this->denormalizer = new ConfigurationDenormalizer(
            $this->authorListNormalizer->reveal(),
            $this->packageListNormalizer->reveal(),
            $this->suggestedPackageListNormalizer->reveal(),
            $this->supportListNormalizer->reveal(),
            $this->autoloadListNormalizer->reveal(),
            $this->scriptListNormalizer->reveal()
        );
    }

    public function testDenormalize()
    {
        $rawConfiguration = [
            ConfigurationNormalizer::KEY_NAME => 'name',
            ConfigurationNormalizer::KEY_TYPE => 'type',
            ConfigurationNormalizer::KEY_LICENSE => 'license',
            ConfigurationNormalizer::KEY_VERSION => 'version',
            ConfigurationNormalizer::KEY_DESCRIPTION => 'description',
            ConfigurationNormalizer::KEY_KEYWORDS => ['keyword1', 'keyword2'],
            ConfigurationNormalizer::KEY_AUTHORS => ['authors'],
            ConfigurationNormalizer::KEY_PROVIDE => ['provided'],
            ConfigurationNormalizer::KEY_SUGGEST => ['suggested'],
            ConfigurationNormalizer::KEY_SUPPORT => ['support'],
            ConfigurationNormalizer::KEY_AUTOLOAD => ['autoload'],
            ConfigurationNormalizer::KEY_AUTOLOAD_DEV => ['autoload_dev'],
            ConfigurationNormalizer::KEY_REQUIRE => ['require'],
            ConfigurationNormalizer::KEY_REQUIRE_DEV => ['require_dev'],
            ConfigurationNormalizer::KEY_SCRIPT => ['script'],
        ];

        $this->authorListNormalizer->denormalize($rawConfiguration[ConfigurationNormalizer::KEY_AUTHORS])
            ->willReturn($rawConfiguration[ConfigurationNormalizer::KEY_AUTHORS])
            ->shouldBeCalled();
        $this->packageListNormalizer->denormalize($rawConfiguration[ConfigurationNormalizer::KEY_PROVIDE])
            ->willReturn($rawConfiguration[ConfigurationNormalizer::KEY_PROVIDE])
            ->shouldBeCalled();
        $this->suggestedPackageListNormalizer->denormalize($rawConfiguration[ConfigurationNormalizer::KEY_SUGGEST])
            ->willReturn($rawConfiguration[ConfigurationNormalizer::KEY_SUGGEST])
            ->shouldBeCalled();
        $this->supportListNormalizer->denormalize($rawConfiguration[ConfigurationNormalizer::KEY_SUPPORT])
            ->willReturn($rawConfiguration[ConfigurationNormalizer::KEY_SUPPORT])
            ->shouldBeCalled();
        $this->autoloadListNormalizer->denormalize($rawConfiguration[ConfigurationNormalizer::KEY_AUTOLOAD])
            ->willReturn($rawConfiguration[ConfigurationNormalizer::KEY_AUTOLOAD])
            ->shouldBeCalled();
        $this->autoloadListNormalizer->denormalize($rawConfiguration[ConfigurationNormalizer::KEY_AUTOLOAD_DEV])
            ->willReturn($rawConfiguration[ConfigurationNormalizer::KEY_AUTOLOAD_DEV])
            ->shouldBeCalled();
        $this->packageListNormalizer->denormalize($rawConfiguration[ConfigurationNormalizer::KEY_REQUIRE])
            ->willReturn($rawConfiguration[ConfigurationNormalizer::KEY_REQUIRE])
            ->shouldBeCalled();
        $this->packageListNormalizer->denormalize($rawConfiguration[ConfigurationNormalizer::KEY_REQUIRE_DEV])
            ->willReturn($rawConfiguration[ConfigurationNormalizer::KEY_REQUIRE_DEV])
            ->shouldBeCalled();
        $this->scriptListNormalizer->denormalize($rawConfiguration[ConfigurationNormalizer::KEY_SCRIPT])
            ->willReturn($rawConfiguration[ConfigurationNormalizer::KEY_SCRIPT])
            ->shouldBeCalled();

        $configuration = $this->denormalizer->denormalize($rawConfiguration);

        $this->assertSame($rawConfiguration[ConfigurationNormalizer::KEY_NAME], $configuration->getPackageName());
        $this->assertSame($rawConfiguration[ConfigurationNormalizer::KEY_TYPE], $configuration->getType());
        $this->assertSame($rawConfiguration[ConfigurationNormalizer::KEY_LICENSE], $configuration->getLicense());
        $this->assertSame(
            $rawConfiguration[ConfigurationNormalizer::KEY_DESCRIPTION],
            $configuration->getDescription()
        );
        $this->assertSame($rawConfiguration[ConfigurationNormalizer::KEY_KEYWORDS], $configuration->getKeywordList());
        $this->assertSame($rawConfiguration[ConfigurationNormalizer::KEY_AUTHORS], $configuration->getAuthorList());
        $this->assertSame(
            $rawConfiguration[ConfigurationNormalizer::KEY_PROVIDE],
            $configuration->getProvidedPackageList()
        );
        $this->assertSame(
            $rawConfiguration[ConfigurationNormalizer::KEY_SUGGEST],
            $configuration->getSuggestedPackageList()
        );
        $this->assertSame($rawConfiguration[ConfigurationNormalizer::KEY_SUPPORT], $configuration->getSupportList());
        $this->assertSame($rawConfiguration[ConfigurationNormalizer::KEY_AUTOLOAD], $configuration->getAutoloadList());
        $this->assertSame(
            $rawConfiguration[ConfigurationNormalizer::KEY_AUTOLOAD_DEV],
            $configuration->getAutoloadDevList()
        );
        $this->assertSame(
            $rawConfiguration[ConfigurationNormalizer::KEY_REQUIRE],
            $configuration->getRequiredPackageList()
        );
        $this->assertSame(
            $rawConfiguration[ConfigurationNormalizer::KEY_REQUIRE_DEV],
            $configuration->getRequiredDevPackageList()
        );
        $this->assertSame($rawConfiguration[ConfigurationNormalizer::KEY_SCRIPT], $configuration->getScriptList());
    }

    public function testDenormalizeWithDefaultValues()
    {
        $rawConfiguration = [];

        $this->authorListNormalizer->denormalize(Argument::cetera())
            ->shouldNotBeCalled();
        $this->packageListNormalizer->denormalize(Argument::cetera())
            ->shouldNotBeCalled();
        $this->suggestedPackageListNormalizer->denormalize(Argument::cetera())
            ->shouldNotBeCalled();
        $this->supportListNormalizer->denormalize(Argument::cetera())
            ->shouldNotBeCalled();
        $this->autoloadListNormalizer->denormalize(Argument::cetera())
            ->shouldNotBeCalled();
        $this->autoloadListNormalizer->denormalize(Argument::cetera())
            ->shouldNotBeCalled();
        $this->packageListNormalizer->denormalize(Argument::cetera())
            ->shouldNotBeCalled();
        $this->packageListNormalizer->denormalize(Argument::cetera())
            ->shouldNotBeCalled();
        $this->scriptListNormalizer->denormalize(Argument::cetera())
            ->shouldNotBeCalled();

        $configuration = $this->denormalizer->denormalize($rawConfiguration);

        $this->assertSame(null, $configuration->getPackageName());
        $this->assertSame(null, $configuration->getType());
        $this->assertSame(null, $configuration->getLicense());
        $this->assertSame(null, $configuration->getDescription());
        $this->assertSame([], $configuration->getKeywordList());
        $this->assertSame([], $configuration->getAuthorList());
        $this->assertSame([], $configuration->getProvidedPackageList());
        $this->assertSame([], $configuration->getSuggestedPackageList());
        $this->assertSame([], $configuration->getSupportList());
        $this->assertSame([], $configuration->getAutoloadList());
        $this->assertSame([], $configuration->getAutoloadDevList());
        $this->assertSame([], $configuration->getRequiredPackageList());
        $this->assertSame([], $configuration->getRequiredDevPackageList());
        $this->assertSame([], $configuration->getScriptList());
    }
}
