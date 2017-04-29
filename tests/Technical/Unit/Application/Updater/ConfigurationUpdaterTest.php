<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Updater;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Updater\AuthorListUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\ConfigurationUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\KeywordListUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\ListUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\PlainValueUpdater;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class ConfigurationUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /** @var PlainValueUpdater|ObjectProphecy */
    private $plainValueUpdater;
    /** @var KeywordListUpdater|ObjectProphecy */
    private $keywordListUpdater;
    /** @var ListUpdater|ObjectProphecy */
    private $listUpdater;
    /** @var AuthorListUpdater|ObjectProphecy */
    private $authorListUpdater;
    /** @var ConfigurationUpdater */
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

        $this->updater = new ConfigurationUpdater(
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

        /** @var Configuration|ObjectProphecy $baseConfiguration */
        $baseConfiguration = $this->prophesize(Configuration::class);
        /** @var Configuration|ObjectProphecy $newConfiguration */
        $newConfiguration = $this->prophesize(Configuration::class);

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
            $scriptList
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
            $scriptList
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
            $scriptList
        );

        $updatedConfiguration = $this->updater->update($baseConfiguration->reveal(), $newConfiguration->reveal());

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
    }

    /**
     * @param Configuration|ObjectProphecy $newConfiguration
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
     */
    protected function configureEntity(
        ObjectProphecy $newConfiguration,
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
        array $scriptList
    ) {
        $newConfiguration->getPackageName()
            ->willReturn($packageName)
            ->shouldBeCalled();
        $newConfiguration->getType()
            ->willReturn($type)
            ->shouldBeCalled();
        $newConfiguration->getLicense()
            ->willReturn($license)
            ->shouldBeCalled();
        $newConfiguration->getPackageVersion()
            ->willReturn($packageVersion)
            ->shouldBeCalled();
        $newConfiguration->getDescription()
            ->willReturn($description)
            ->shouldBeCalled();
        $newConfiguration->getKeywordList()
            ->willReturn($keywordList)
            ->shouldBeCalled();
        $newConfiguration->getAuthorList()
            ->willReturn($authorList)
            ->shouldBeCalled();
        $newConfiguration->getProvidedPackageList()
            ->willReturn($providedPackageList)
            ->shouldBeCalled();
        $newConfiguration->getSuggestedPackageList()
            ->willReturn($suggestedPackageList)
            ->shouldBeCalled();
        $newConfiguration->getSupportList()
            ->willReturn($supportList)
            ->shouldBeCalled();
        $newConfiguration->getAutoloadList()
            ->willReturn($autoloadList)
            ->shouldBeCalled();
        $newConfiguration->getAutoloadDevList()
            ->willReturn($autoloadDevList)
            ->shouldBeCalled();
        $newConfiguration->getRequiredPackageList()
            ->willReturn($requiredPackageList)
            ->shouldBeCalled();
        $newConfiguration->getRequiredDevPackageList()
            ->willReturn($requiredDevPackageList)
            ->shouldBeCalled();
        $newConfiguration->getScriptList()
            ->willReturn($scriptList)
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
        array $scriptList
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
    }
}
