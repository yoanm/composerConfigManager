<?php
namespace Yoanm\ComposerConfigManager\Application\Updater;

use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class ConfigurationUpdater
{
    /** @var PlainValueUpdater */
    private $plainValueUpdater;
    /** @var KeywordListUpdater */
    private $keywordListUpdater;
    /** @var ListUpdater */
    private $listUpdater;
    /** @var AuthorListUpdater */
    private $authorListUpdater;

    public function __construct(
        PlainValueUpdater $plainValueUpdater,
        KeywordListUpdater $keywordListUpdater,
        ListUpdater $listUpdater,
        AuthorListUpdater $authorListUpdater
    ) {
        $this->plainValueUpdater = $plainValueUpdater;
        $this->keywordListUpdater = $keywordListUpdater;
        $this->listUpdater = $listUpdater;
        $this->authorListUpdater = $authorListUpdater;
    }

    /**
     * @param Configuration[] $configurationList
     *
     * @return Configuration
     */
    public function update(array $configurationList)
    {
        $newConfiguration = array_pop($configurationList);

        while (count($configurationList) > 0) {
            $baseConfiguration = array_pop($configurationList);
            $newConfiguration = $this->merge($baseConfiguration, $newConfiguration);
        }

        return $newConfiguration;
    }


    /**
     * @param Configuration $baseConfiguration
     * @param Configuration $newConfiguration
     *
     * @return Configuration
     */
    public function merge(Configuration $baseConfiguration, Configuration $newConfiguration)
    {
        return new Configuration(
            $this->plainValueUpdater->update(
                $newConfiguration->getPackageName(),
                $baseConfiguration->getPackageName()
            ),
            $this->plainValueUpdater->update(
                $newConfiguration->getType(),
                $baseConfiguration->getType()
            ),
            $this->plainValueUpdater->update(
                $newConfiguration->getLicense(),
                $baseConfiguration->getLicense()
            ),
            $this->plainValueUpdater->update(
                $newConfiguration->getPackageVersion(),
                $baseConfiguration->getPackageVersion()
            ),
            $this->plainValueUpdater->update(
                $newConfiguration->getDescription(),
                $baseConfiguration->getDescription()
            ),
            $this->keywordListUpdater->update(
                $newConfiguration->getKeywordList(),
                $baseConfiguration->getKeywordList()
            ),
            $this->authorListUpdater->update($newConfiguration->getAuthorList(), $baseConfiguration->getAuthorList()),
            $this->listUpdater->update(
                $newConfiguration->getProvidedPackageList(),
                $baseConfiguration->getProvidedPackageList()
            ),
            $this->listUpdater->update(
                $newConfiguration->getSuggestedPackageList(),
                $baseConfiguration->getSuggestedPackageList()
            ),
            $this->listUpdater->update($newConfiguration->getSupportList(), $baseConfiguration->getSupportList()),
            $this->listUpdater->update($newConfiguration->getAutoloadList(), $baseConfiguration->getAutoloadList()),
            $this->listUpdater->update(
                $newConfiguration->getAutoloadDevList(),
                $baseConfiguration->getAutoloadDevList()
            ),
            $this->listUpdater->update(
                $newConfiguration->getRequiredPackageList(),
                $baseConfiguration->getRequiredPackageList()
            ),
            $this->listUpdater->update(
                $newConfiguration->getRequiredDevPackageList(),
                $baseConfiguration->getRequiredDevPackageList()
            ),
            $this->listUpdater->update($newConfiguration->getScriptList(), $baseConfiguration->getScriptList())
        );
    }
}
