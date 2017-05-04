<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AuthorListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AutoloadListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\PackageListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ScriptListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\SuggestedPackageListNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\SupportListNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationNormalizerTest extends \PHPUnit_Framework_TestCase
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

    /** @var ConfigurationNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->authorListNormalizer = $this->prophesize(AuthorListNormalizer::class);
        $this->packageListNormalizer = $this->prophesize(PackageListNormalizer::class);
        $this->suggestedPackageListNormalizer = $this->prophesize(SuggestedPackageListNormalizer::class);
        $this->supportListNormalizer = $this->prophesize(SupportListNormalizer::class);
        $this->autoloadListNormalizer = $this->prophesize(AutoloadListNormalizer::class);
        $this->scriptListNormalizer = $this->prophesize(ScriptListNormalizer::class);

        $this->normalizer = new ConfigurationNormalizer(
            $this->authorListNormalizer->reveal(),
            $this->packageListNormalizer->reveal(),
            $this->suggestedPackageListNormalizer->reveal(),
            $this->supportListNormalizer->reveal(),
            $this->autoloadListNormalizer->reveal(),
            $this->scriptListNormalizer->reveal()
        );
    }

    /**
     * @dataProvider getTestNormalizeData
     *
     * @param string      $packageName
     * @param string      $packageType
     * @param string      $packageLicense
     * @param null|string $packageVersion
     * @param null|string $description
     * @param array       $keywordList
     * @param array       $authorList
     * @param array       $providedPackageList
     * @param array       $suggestedPackageList
     * @param array       $supportList
     * @param array       $requiredPackageList
     * @param array       $requiredDevPackageList
     * @param array       $autoloadList
     * @param array       $autoloadDevList
     * @param array       $scriptList
     */
    public function testNormalize(
        $packageName,
        $packageType,
        $packageLicense,
        $packageVersion = null,
        $description = null,
        $keywordList = [],
        $authorList = [],
        $providedPackageList = [],
        $suggestedPackageList = [],
        $supportList = [],
        $requiredPackageList = [],
        $requiredDevPackageList = [],
        $autoloadList = [],
        $autoloadDevList = [],
        $scriptList = []
    ) {
        $configuration = $this->buildConfiguration(
            $packageName,
            $packageType,
            $packageLicense,
            $packageVersion,
            $description,
            $keywordList,
            $authorList,
            $providedPackageList,
            $suggestedPackageList,
            $supportList,
            $requiredPackageList,
            $requiredDevPackageList,
            $autoloadList,
            $autoloadDevList,
            $scriptList
        );

        $this->assertSame(
            $this->buildExpected(
                $packageName,
                $packageType,
                $packageLicense,
                $packageVersion,
                $description,
                $keywordList,
                $authorList,
                $providedPackageList,
                $suggestedPackageList,
                $supportList,
                $requiredPackageList,
                $requiredDevPackageList,
                $autoloadList,
                $autoloadDevList,
                $scriptList
            ),
            $this->normalizer->normalize($configuration->reveal())
        );
    }

    public function getTestNormalizeData()
    {
        return [
            'minimal' => [
                'packageName' => 'packageName',
                'packageType' => 'packageType',
                'packageLicense' => 'packageLicense',
            ],
            'basic' => [
                'packageName' => 'packageName',
                'packageType' => 'packageType',
                'packageLicense' => 'packageLicense',
                'packageVersion' => 'packageVersion',
                'description' => 'description',
            ],
            'all' => [
                'packageName' => 'packageName',
                'packageType' => 'packageType',
                'packageLicense' => 'packageLicense',
                'packageVersion' => 'packageVersion',
                'description' => 'description',
                'keywordList' => ['keywordList'],
                'authorList' => ['AuthorList'],
                'providedPackageList' => ['ProvidedPackageList'],
                'suggestedPackageList' => ['SuggestedPackageList'],
                'supportList' => ['SupportList'],
                'requiredPackageList' => ['RequiredPackageList'],
                'requiredDevPackageList' => ['RequiredDevPackageList'],
                'autoloadList' => ['AutoloadList'],
                'autoloadDevList' => ['AutoloadDevList'],
                'scriptList' => ['ScriptList'],
            ],
            'many entries' => [
                'packageName' => 'packageName',
                'packageType' => 'packageType',
                'packageLicense' => 'packageLicense',
                'keywordList' => ['keywordList1', 'keywordList2'],
                'authorList' => ['AuthorList1', 'AuthorList2'],
                'providedPackageList' => ['ProvidedPackageList1', 'ProvidedPackageList2'],
                'suggestedPackageList' => ['SuggestedPackageList1', 'SuggestedPackageList2'],
                'supportList' => ['SupportList1', 'SupportList2'],
                'requiredPackageList' => ['RequiredPackageList1', 'RequiredPackageList2'],
                'requiredDevPackageList' => ['RequiredDevPackageList1', 'RequiredDevPackageList2'],
                'autoloadList' => ['AutoloadList1', 'AutoloadList2'],
                'autoloadDevList' => ['AutoloadDevList1', 'AutoloadDevList2'],
                'scriptList' => ['ScriptList1', 'ScriptList2'],
            ],
        ];
    }

    /**
     * @param string      $packageName
     * @param string      $packageType
     * @param string      $packageLicense
     * @param null|string $packageVersion
     * @param null|string $description
     * @param array       $keywordList
     * @param array       $authorList
     * @param array       $providedPackageList
     * @param array       $suggestedPackageList
     * @param array       $supportList
     * @param array       $requiredPackageList
     * @param array       $requiredDevPackageList
     * @param array       $autoloadList
     * @param array       $autoloadDevList
     * @param array       $scriptList
     *
     * @return Configuration|ObjectProphecy
     */
    protected function buildConfiguration(
        $packageName,
        $packageType,
        $packageLicense,
        $packageVersion = null,
        $description = null,
        $keywordList = [],
        $authorList = [],
        $providedPackageList = [],
        $suggestedPackageList = [],
        $supportList = [],
        $requiredPackageList = [],
        $requiredDevPackageList = [],
        $autoloadList = [],
        $autoloadDevList = [],
        $scriptList = []
    ) {
        /** @var Configuration|ObjectProphecy $configuration */
        $configuration = $this->prophesize(Configuration::class);

        $configuration->getPackageName()
            ->willReturn($packageName)
            ->shouldBeCalled();
        $configuration->getType()
            ->willReturn($packageType)
            ->shouldBeCalled();
        $configuration->getLicense()
            ->willReturn($packageLicense)
            ->shouldBeCalled();
        $configuration->getPackageVersion()
            ->willReturn($packageVersion)
            ->shouldBeCalled();
        $configuration->getDescription()
            ->willReturn($description)
            ->shouldBeCalled();
        $configuration->getKeywordList()
            ->willReturn($keywordList)
            ->shouldBeCalled();
        $configuration->getAuthorList()
            ->willReturn($authorList)
            ->shouldBeCalled();
        $configuration->getProvidedPackageList()
            ->willReturn($providedPackageList)
            ->shouldBeCalled();
        $configuration->getSuggestedPackageList()
            ->willReturn($suggestedPackageList)
            ->shouldBeCalled();
        $configuration->getSupportList()
            ->willReturn($supportList)
            ->shouldBeCalled();
        $configuration->getRequiredPackageList()
            ->willReturn($requiredPackageList)
            ->shouldBeCalled();
        $configuration->getRequiredDevPackageList()
            ->willReturn($requiredDevPackageList)
            ->shouldBeCalled();
        $configuration->getAutoloadList()
            ->willReturn($autoloadList)
            ->shouldBeCalled();
        $configuration->getAutoloadDevList()
            ->willReturn($autoloadDevList)
            ->shouldBeCalled();
        $configuration->getScriptList()
            ->willReturn($scriptList)
            ->shouldBeCalled();

        $this->authorListNormalizer->normalize($authorList)
            ->willReturn($authorList)
            ->shouldBeCalled();
        $this->packageListNormalizer->normalize($providedPackageList)
            ->willReturn($providedPackageList)
            ->shouldBeCalled();
        $this->suggestedPackageListNormalizer->normalize($suggestedPackageList)
            ->willReturn($suggestedPackageList)
            ->shouldBeCalled();
        $this->supportListNormalizer->normalize($supportList)
            ->willReturn($supportList)
            ->shouldBeCalled();
        $this->packageListNormalizer->normalize($requiredPackageList)
            ->willReturn($requiredPackageList)
            ->shouldBeCalled();
        $this->packageListNormalizer->normalize($requiredDevPackageList)
            ->willReturn($requiredDevPackageList)
            ->shouldBeCalled();
        $this->autoloadListNormalizer->normalize($autoloadList)
            ->willReturn($autoloadList)
            ->shouldBeCalled();
        $this->autoloadListNormalizer->normalize($autoloadDevList)
            ->willReturn($autoloadDevList)
            ->shouldBeCalled();
        $this->scriptListNormalizer->normalize($scriptList)
            ->willReturn($scriptList)
            ->shouldBeCalled();

        return $configuration;
    }

    /**
     * @param string      $packageName
     * @param string      $packageType
     * @param string      $packageLicense
     * @param null|string $packageVersion
     * @param null|string $description
     * @param array       $keywordList
     * @param array       $authorList
     * @param array       $providedPackageList
     * @param array       $suggestedPackageList
     * @param array       $supportList
     * @param array       $requiredPackageList
     * @param array       $requiredDevPackageList
     * @param array       $autoloadList
     * @param array       $autoloadDevList
     * @param array       $scriptList
     *
     * @return Configuration|ObjectProphecy
     */
    protected function buildExpected(
        $packageName,
        $packageType,
        $packageLicense,
        $packageVersion = null,
        $description = null,
        $keywordList = [],
        $authorList = [],
        $providedPackageList = [],
        $suggestedPackageList = [],
        $supportList = [],
        $requiredPackageList = [],
        $requiredDevPackageList = [],
        $autoloadList = [],
        $autoloadDevList = [],
        $scriptList = []
    ) {

        $expected = [
            ConfigurationFile::KEY_NAME => $packageName,
            ConfigurationFile::KEY_TYPE =>$packageType,
            ConfigurationFile::KEY_LICENSE => $packageLicense
        ];

        if ($packageVersion) {
            $expected[ConfigurationFile::KEY_VERSION] = $packageVersion;
        }
        if ($description) {
            $expected[ConfigurationFile::KEY_DESCRIPTION] = $description;
        }
        if (count($keywordList)) {
            $expected[ConfigurationFile::KEY_KEYWORDS] = $keywordList;
        }
        if (count($authorList)) {
            $expected[ConfigurationFile::KEY_AUTHORS] = $authorList;
        }
        if (count($providedPackageList)) {
            $expected[ConfigurationFile::KEY_PROVIDE] = $providedPackageList;
        }
        if (count($suggestedPackageList)) {
            $expected[ConfigurationFile::KEY_SUGGEST] = $suggestedPackageList;
        }
        if (count($supportList)) {
            $expected[ConfigurationFile::KEY_SUPPORT] = $supportList;
        }
        if (count($requiredPackageList)) {
            $expected[ConfigurationFile::KEY_REQUIRE] = $requiredPackageList;
        }
        if (count($requiredDevPackageList)) {
            $expected[ConfigurationFile::KEY_REQUIRE_DEV] = $requiredDevPackageList;
        }
        if (count($autoloadList)) {
            $expected[ConfigurationFile::KEY_AUTOLOAD] = $autoloadList;
        }
        if (count($autoloadDevList)) {
            $expected[ConfigurationFile::KEY_AUTOLOAD_DEV] = $autoloadDevList;
        }
        if (count($scriptList)) {
            $expected[ConfigurationFile::KEY_SCRIPTS] = $scriptList;
        }

        return $expected;
    }
}
