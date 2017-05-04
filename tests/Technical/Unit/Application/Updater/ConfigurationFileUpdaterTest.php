<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Updater;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Updater\AuthorListUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\ConfigurationFileUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\KeywordListUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\ListUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\PlainValueUpdater;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

/**
 * @covers Yoanm\ComposerConfigManager\Application\Updater\ConfigurationFileUpdater
 */
class ConfigurationFileUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /** @var PlainValueUpdater|ObjectProphecy */
    private $plainValueUpdater;
    /** @var KeywordListUpdater|ObjectProphecy */
    private $keywordListUpdater;
    /** @var ListUpdater|ObjectProphecy */
    private $listUpdater;
    /** @var AuthorListUpdater|ObjectProphecy */
    private $authorListUpdater;
    /** @var ConfigurationFileUpdater */
    private $updater;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->plainValueUpdater = $this->prophesize(PlainValueUpdater::class);
        $this->keywordListUpdater = $this->prophesize(KeywordListUpdater::class);
        $this->listUpdater = $this->prophesize(ListUpdater::class);
        $this->authorListUpdater = $this->prophesize(AuthorListUpdater::class);

        $this->updater = new ConfigurationFileUpdater(
            $this->plainValueUpdater->reveal(),
            $this->keywordListUpdater->reveal(),
            $this->listUpdater->reveal(),
            $this->authorListUpdater->reveal()
        );
    }

    public function testUpdate()
    {
        $packageName = 'packageName';
        $type = 'type';
        $license = 'license';
        $packageVersion = 'packageVersion';
        $description = 'description';
        $keywordList = ['keywordList'];
        $authorList = ['authorList'];
        $providedPackageList = ['providedPackageList'];
        $suggestedPackageList = ['suggestedPackageList'];
        $supportList = ['supportList'];
        $autoloadList = ['autoloadList'];
        $autoloadDevList = ['autoloadDevList'];
        $requiredPackageList = ['requiredPackageList'];
        $requiredDevPackageList = ['requiredDevPackageList'];
        $scriptList = ['scriptList'];
        $unmanagedPropertyList = [
            'a' => 'b',
            'c' => [
                'd' => [
                    'e' => 'f'
                ]
            ],
            'g' => ['h', 'i']
        ];

        $baseKeyList = ['key_1', 'key_2'];
        $newKeyList = ['key_1', 'key_0'];

        /** @var ConfigurationFile|ObjectProphecy $baseConfigurationFile */
        $baseConfigurationFile = $this->prophesize(ConfigurationFile::class);
        /** @var ConfigurationFile|ObjectProphecy $newConfigurationFile */
        $newConfigurationFile = $this->prophesize(ConfigurationFile::class);
        /** @var Configuration|ObjectProphecy $baseConfiguration */
        $baseConfiguration = $this->prophesize(Configuration::class);
        /** @var Configuration|ObjectProphecy $newConfiguration */
        $newConfiguration = $this->prophesize(Configuration::class);

        $baseConfigurationFile->getConfiguration()
            ->willReturn($baseConfiguration->reveal())
            ->shouldBeCalled();
        $newConfigurationFile->getConfiguration()
            ->willReturn($newConfiguration->reveal())
            ->shouldBeCalled();

        $baseConfigurationFile->getKeyList()
            ->willReturn($baseKeyList)
            ->shouldBeCalled();
        $newConfigurationFile->getKeyList()
            ->willReturn($newKeyList)
            ->shouldBeCalled();

        $this->configureEntity(
            $newConfiguration,
            $packageName,
            $type,
            $license,
            $packageVersion,
            $description,
            $keywordList,
            $authorList,
            $providedPackageList,
            $suggestedPackageList,
            $supportList,
            $autoloadList,
            $autoloadDevList,
            $requiredPackageList,
            $requiredDevPackageList,
            $scriptList,
            $unmanagedPropertyList
        );
        $this->configureEntity(
            $baseConfiguration,
            $packageName,
            $type,
            $license,
            $packageVersion,
            $description,
            $keywordList,
            $authorList,
            $providedPackageList,
            $suggestedPackageList,
            $supportList,
            $autoloadList,
            $autoloadDevList,
            $requiredPackageList,
            $requiredDevPackageList,
            $scriptList,
            $unmanagedPropertyList
        );
        $this->configureUpdater(
            $packageName,
            $type,
            $license,
            $packageVersion,
            $description,
            $keywordList,
            $authorList,
            $providedPackageList,
            $suggestedPackageList,
            $supportList,
            $autoloadList,
            $autoloadDevList,
            $requiredPackageList,
            $requiredDevPackageList,
            $scriptList,
            $unmanagedPropertyList
        );

        $updatedConfigurationFile = $this->updater->update([
            $baseConfigurationFile->reveal(),
            $newConfigurationFile->reveal()
        ]);

        $this->assertInstanceOf(ConfigurationFile::class, $updatedConfigurationFile);

        $updatedConfiguration = $updatedConfigurationFile->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $updatedConfiguration);
        $this->assertSame($packageName, $updatedConfiguration->getPackageName());
        $this->assertSame($type, $updatedConfiguration->getType());
        $this->assertSame($license, $updatedConfiguration->getLicense());
        $this->assertSame($packageVersion, $updatedConfiguration->getPackageVersion());
        $this->assertSame($description, $updatedConfiguration->getDescription());
        $this->assertSame($keywordList, $updatedConfiguration->getKeywordList());
        $this->assertSame($authorList, $updatedConfiguration->getAuthorList());
        $this->assertSame($providedPackageList, $updatedConfiguration->getProvidedPackageList());
        $this->assertSame($suggestedPackageList, $updatedConfiguration->getSuggestedPackageList());
        $this->assertSame($supportList, $updatedConfiguration->getSupportList());
        $this->assertSame($autoloadList, $updatedConfiguration->getAutoloadList());
        $this->assertSame($autoloadDevList, $updatedConfiguration->getAutoloadDevList());
        $this->assertSame($requiredPackageList, $updatedConfiguration->getRequiredPackageList());
        $this->assertSame($requiredDevPackageList, $updatedConfiguration->getRequiredDevPackageList());
        $this->assertSame($scriptList, $updatedConfiguration->getScriptList());
        $this->assertSame($unmanagedPropertyList, $updatedConfiguration->getUnmanagedPropertyList());

        $this->assertSame(
            ['key_1', 'key_2', 'key_0'],
            $updatedConfigurationFile->getKeyList()
        );
    }

    /**
     * @param Configuration|ObjectProphecy $configuration
     * @param string|null                  $packageName
     * @param string|null                  $$type
     * @param string|null                  $license
     * @param string|null                  $packageVersion
     * @param string|null                  $description
     * @param array                        $keywordList
     * @param array                        $authorList
     * @param array                        $providedPackageList
     * @param array                        $suggestedPackageList
     * @param array                        $supportList
     * @param array                        $autoloadList
     * @param array                        $autoloadDevList
     * @param array                        $requiredPackageList
     * @param array                        $requiredDevPackageList
     * @param array                        $scriptList
     * @param array                        $unmanagedPropertyList
     */
    protected function configureEntity(
        ObjectProphecy $configuration,
        $packageName = null,
        $type = null,
        $license = null,
        $packageVersion = null,
        $description = null,
        array $keywordList = [],
        array $authorList = [],
        array $providedPackageList = [],
        array $suggestedPackageList = [],
        array $supportList = [],
        array $autoloadList = [],
        array $autoloadDevList = [],
        array $requiredPackageList = [],
        array $requiredDevPackageList = [],
        array $scriptList = [],
        array $unmanagedPropertyList = []
    ) {
        $configuration->getPackageName()
            ->willReturn($packageName)
            ->shouldBeCalled();
        $configuration->getType()
            ->willReturn($type)
            ->shouldBeCalled();
        $configuration->getLicense()
            ->willReturn($license)
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
        $configuration->getAutoloadList()
            ->willReturn($autoloadList)
            ->shouldBeCalled();
        $configuration->getAutoloadDevList()
            ->willReturn($autoloadDevList)
            ->shouldBeCalled();
        $configuration->getRequiredPackageList()
            ->willReturn($requiredPackageList)
            ->shouldBeCalled();
        $configuration->getRequiredDevPackageList()
            ->willReturn($requiredDevPackageList)
            ->shouldBeCalled();
        $configuration->getScriptList()
            ->willReturn($scriptList)
            ->shouldBeCalled();
        $configuration->getUnmanagedPropertyList()
            ->willReturn($unmanagedPropertyList)
            ->shouldBeCalled();
    }

    /**
     * @param string|null $packageName
     * @param string|null $type
     * @param string|null $license
     * @param string|null $packageVersion
     * @param string|null $description
     * @param array       $keywordList
     * @param array       $authorList
     * @param array       $providedPackageList
     * @param array       $suggestedPackageList
     * @param array       $supportList
     * @param array       $autoloadList
     * @param array       $autoloadDevList
     * @param array       $requiredPackageList
     * @param array       $requiredDevPackageList
     * @param array       $scriptList
     * @param array       $unmanagedPropertyList
     */
    public function configureUpdater(
        $packageName,
        $type,
        $license,
        $packageVersion,
        $description,
        array $keywordList,
        array $authorList,
        array $providedPackageList,
        array $suggestedPackageList,
        array $supportList,
        array $autoloadList,
        array $autoloadDevList,
        array $requiredPackageList,
        array $requiredDevPackageList,
        array $scriptList,
        array $unmanagedPropertyList
    ) {
        $this->plainValueUpdater->update($packageName, $packageName)
            ->willReturn($packageName)
            ->shouldBeCalled();
        $this->plainValueUpdater->update($type, $type)
            ->willReturn($type)
            ->shouldBeCalled();
        $this->plainValueUpdater->update($license, $license)
            ->willReturn($license)
            ->shouldBeCalled();
        $this->plainValueUpdater->update($packageVersion, $packageVersion)
            ->willReturn($packageVersion)
            ->shouldBeCalled();
        $this->plainValueUpdater->update($description, $description)
            ->willReturn($description)
            ->shouldBeCalled();
        $this->keywordListUpdater->update($keywordList, $keywordList)
            ->willReturn($keywordList)
            ->shouldBeCalled();
        $this->authorListUpdater->update($authorList, $authorList)
            ->willReturn($authorList)
            ->shouldBeCalled();
        $this->listUpdater->update($providedPackageList, $providedPackageList)
            ->willReturn($providedPackageList)
            ->shouldBeCalled();
        $this->listUpdater->update($suggestedPackageList, $suggestedPackageList)
            ->willReturn($suggestedPackageList)
            ->shouldBeCalled();
        $this->listUpdater->update($supportList, $supportList)
            ->willReturn($supportList)
            ->shouldBeCalled();
        $this->listUpdater->update($autoloadList, $autoloadList)
            ->willReturn($autoloadList)
            ->shouldBeCalled();
        $this->listUpdater->update($autoloadDevList, $autoloadDevList)
            ->willReturn($autoloadDevList)
            ->shouldBeCalled();
        $this->listUpdater->update($requiredPackageList, $requiredPackageList)
            ->willReturn($requiredPackageList)
            ->shouldBeCalled();
        $this->listUpdater->update($requiredDevPackageList, $requiredDevPackageList)
            ->willReturn($requiredDevPackageList)
            ->shouldBeCalled();
        $this->listUpdater->update($scriptList, $scriptList)
            ->willReturn($scriptList)
            ->shouldBeCalled();
        $this->listUpdater->updateRaw($unmanagedPropertyList, $unmanagedPropertyList)
            ->willReturn($unmanagedPropertyList)
            ->shouldBeCalled();
    }
}
