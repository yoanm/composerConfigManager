<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AuthorListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AutoloadListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationDenormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\PackageListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ScriptListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\SuggestedPackageListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\SupportListNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

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
            ConfigurationFile::KEY_NAME => 'name',
            ConfigurationFile::KEY_TYPE => 'type',
            ConfigurationFile::KEY_LICENSE => 'license',
            ConfigurationFile::KEY_VERSION => 'version',
            ConfigurationFile::KEY_DESCRIPTION => 'description',
            ConfigurationFile::KEY_KEYWORDS => ['keyword1', 'keyword2'],
            ConfigurationFile::KEY_AUTHORS => ['authors'],
            ConfigurationFile::KEY_PROVIDE => ['provided'],
            ConfigurationFile::KEY_SUGGEST => ['suggested'],
            ConfigurationFile::KEY_SUPPORT => ['support'],
            ConfigurationFile::KEY_AUTOLOAD => ['autoload'],
            ConfigurationFile::KEY_AUTOLOAD_DEV => ['autoload_dev'],
            ConfigurationFile::KEY_REQUIRE => ['require'],
            ConfigurationFile::KEY_REQUIRE_DEV => ['require_dev'],
            ConfigurationFile::KEY_SCRIPTS => ['script'],
            'a' => 'b',
            'c' => [
                'd' => [
                    'e' => 'f'
                ]
            ],
            'g' => ['h', 'i']
        ];

        $this->authorListNormalizer->denormalize($rawConfiguration[ConfigurationFile::KEY_AUTHORS])
            ->willReturn($rawConfiguration[ConfigurationFile::KEY_AUTHORS])
            ->shouldBeCalled();
        $this->packageListNormalizer->denormalize($rawConfiguration[ConfigurationFile::KEY_PROVIDE])
            ->willReturn($rawConfiguration[ConfigurationFile::KEY_PROVIDE])
            ->shouldBeCalled();
        $this->suggestedPackageListNormalizer->denormalize($rawConfiguration[ConfigurationFile::KEY_SUGGEST])
            ->willReturn($rawConfiguration[ConfigurationFile::KEY_SUGGEST])
            ->shouldBeCalled();
        $this->supportListNormalizer->denormalize($rawConfiguration[ConfigurationFile::KEY_SUPPORT])
            ->willReturn($rawConfiguration[ConfigurationFile::KEY_SUPPORT])
            ->shouldBeCalled();
        $this->autoloadListNormalizer->denormalize($rawConfiguration[ConfigurationFile::KEY_AUTOLOAD])
            ->willReturn($rawConfiguration[ConfigurationFile::KEY_AUTOLOAD])
            ->shouldBeCalled();
        $this->autoloadListNormalizer->denormalize($rawConfiguration[ConfigurationFile::KEY_AUTOLOAD_DEV])
            ->willReturn($rawConfiguration[ConfigurationFile::KEY_AUTOLOAD_DEV])
            ->shouldBeCalled();
        $this->packageListNormalizer->denormalize($rawConfiguration[ConfigurationFile::KEY_REQUIRE])
            ->willReturn($rawConfiguration[ConfigurationFile::KEY_REQUIRE])
            ->shouldBeCalled();
        $this->packageListNormalizer->denormalize($rawConfiguration[ConfigurationFile::KEY_REQUIRE_DEV])
            ->willReturn($rawConfiguration[ConfigurationFile::KEY_REQUIRE_DEV])
            ->shouldBeCalled();
        $this->scriptListNormalizer->denormalize($rawConfiguration[ConfigurationFile::KEY_SCRIPTS])
            ->willReturn($rawConfiguration[ConfigurationFile::KEY_SCRIPTS])
            ->shouldBeCalled();

        $configuration = $this->denormalizer->denormalize($rawConfiguration);

        $this->assertSame($rawConfiguration[ConfigurationFile::KEY_NAME], $configuration->getPackageName());
        $this->assertSame($rawConfiguration[ConfigurationFile::KEY_TYPE], $configuration->getType());
        $this->assertSame($rawConfiguration[ConfigurationFile::KEY_LICENSE], $configuration->getLicense());
        $this->assertSame(
            $rawConfiguration[ConfigurationFile::KEY_DESCRIPTION],
            $configuration->getDescription()
        );
        $this->assertSame($rawConfiguration[ConfigurationFile::KEY_KEYWORDS], $configuration->getKeywordList());
        $this->assertSame($rawConfiguration[ConfigurationFile::KEY_AUTHORS], $configuration->getAuthorList());
        $this->assertSame(
            $rawConfiguration[ConfigurationFile::KEY_PROVIDE],
            $configuration->getProvidedPackageList()
        );
        $this->assertSame(
            $rawConfiguration[ConfigurationFile::KEY_SUGGEST],
            $configuration->getSuggestedPackageList()
        );
        $this->assertSame($rawConfiguration[ConfigurationFile::KEY_SUPPORT], $configuration->getSupportList());
        $this->assertSame($rawConfiguration[ConfigurationFile::KEY_AUTOLOAD], $configuration->getAutoloadList());
        $this->assertSame(
            $rawConfiguration[ConfigurationFile::KEY_AUTOLOAD_DEV],
            $configuration->getAutoloadDevList()
        );
        $this->assertSame(
            $rawConfiguration[ConfigurationFile::KEY_REQUIRE],
            $configuration->getRequiredPackageList()
        );
        $this->assertSame(
            $rawConfiguration[ConfigurationFile::KEY_REQUIRE_DEV],
            $configuration->getRequiredDevPackageList()
        );
        $this->assertSame($rawConfiguration[ConfigurationFile::KEY_SCRIPTS], $configuration->getScriptList());

        //Unmanaged properties
        $unmanagedPropertyList = $configuration->getUnmanagedPropertyList();
        $this->assertSame($rawConfiguration['a'], $unmanagedPropertyList['a']);
        $this->assertSame($rawConfiguration['c'], $unmanagedPropertyList['c']);
        $this->assertSame($rawConfiguration['g'], $unmanagedPropertyList['g']);
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
