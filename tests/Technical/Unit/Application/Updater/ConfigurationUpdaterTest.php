<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Updater;

use Yoanm\ComposerConfigManager\Application\Updater\AuthorListUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\ConfigurationUpdater;
use Yoanm\ComposerConfigManager\Application\Updater\ListUpdater;
use Yoanm\ComposerConfigManager\Domain\Model\Author;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationItemInterface;

class ConfigurationUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ListUpdater */
    private $listUpdater;
    /** @var AuthorListUpdater */
    private $authorListUpdater;
    /** @var ConfigurationUpdater */
    private $updater;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->listUpdater = $this->prophesize(ListUpdater::class);
        $this->authorListUpdater = $this->prophesize(AuthorListUpdater::class);

        $this->updater = new ConfigurationUpdater(
            $this->listUpdater->reveal(),
            $this->authorListUpdater->reveal()
        );
    }


    /**
     * @param Configuration $baseConfiguration
     * @param Configuration $newConfiguration
     *
     * @return Configuration
     */
    public function update(Configuration $baseConfiguration, Configuration $newConfiguration)
    {
        return new Configuration(
            $this->updateIfDefined($newConfiguration->getPackageName(), $baseConfiguration->getPackageName()),
            $this->updateIfDefined($newConfiguration->getType(), $baseConfiguration->getType()),
            $this->updateIfDefined($newConfiguration->getLicense(), $baseConfiguration->getLicense()),
            $this->updateIfDefined($newConfiguration->getPackageVersion(), $baseConfiguration->getPackageVersion()),
            $this->updateIfDefined($newConfiguration->getDescription(), $baseConfiguration->getDescription()),
            $this->mergeKeywordList($newConfiguration->getKeywordList(), $baseConfiguration->getKeywordList()),
            $this->updateList($newConfiguration->getAuthorList(), $baseConfiguration->getAuthorList()),
            $this->updateList(
                $newConfiguration->getProvidedPackageList(),
                $baseConfiguration->getProvidedPackageList()
            ),
            $this->updateList(
                $newConfiguration->getSuggestedPackageList(),
                $baseConfiguration->getSuggestedPackageList()
            ),
            $this->updateList($newConfiguration->getSupportList(), $baseConfiguration->getSupportList()),
            $this->updateList($newConfiguration->getAutoloadList(), $baseConfiguration->getAutoloadList()),
            $this->updateList($newConfiguration->getAutoloadDevList(), $baseConfiguration->getAutoloadDevList()),
            $this->updateList(
                $newConfiguration->getRequiredPackageList(),
                $baseConfiguration->getRequiredPackageList()
            ),
            $this->updateList(
                $newConfiguration->getRequiredDevPackageList(),
                $baseConfiguration->getRequiredDevPackageList()
            ),
            $this->updateList($newConfiguration->getScriptList(), $baseConfiguration->getScriptList())
        );
    }

    /**
     * @param string $baseValue
     * @param string $newValue
     *
     * @return string
     */
    protected function updateIfDefined($newValue, $baseValue)
    {
        return $newValue ? $newValue : $baseValue;
    }

    /**
     * @param string[] $newList
     * @param string[] $oldList
     *
     * @return string[]
     */
    protected function mergeKeywordList(array $oldList, array $newList)
    {
        return array_values(
            array_unique(
                array_merge($newList, $oldList)
            )
        );
    }
}
