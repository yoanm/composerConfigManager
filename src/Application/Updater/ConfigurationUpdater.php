<?php
namespace Yoanm\ComposerConfigManager\Application\Updater;

use Yoanm\ComposerConfigManager\Domain\Model\Author;
use Yoanm\ComposerConfigManager\Domain\Model\Autoload;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\Package;
use Yoanm\ComposerConfigManager\Domain\Model\Script;
use Yoanm\ComposerConfigManager\Domain\Model\SuggestedPackage;
use Yoanm\ComposerConfigManager\Domain\Model\Support;

class ConfigurationUpdater
{
    /**
     * @param Configuration $baseConfiguration
     * @param Configuration $newConfiguration
     *
     * @return Configuration
     */
    public function update(Configuration $baseConfiguration, Configuration $newConfiguration)
    {
        return new Configuration(
            $this->updateIfDefined($baseConfiguration->getPackageName(), $newConfiguration->getPackageName()),
            $this->updateIfDefined($baseConfiguration->getType(), $newConfiguration->getType()),
            $this->updateIfDefined($baseConfiguration->getLicense(), $newConfiguration->getLicense()),
            $this->updateIfDefined($baseConfiguration->getPackageVersion(), $newConfiguration->getPackageVersion()),
            $this->updateIfDefined($baseConfiguration->getDescription(), $newConfiguration->getDescription()),
            $this->mergeValueList($baseConfiguration->getKeywordList(), $newConfiguration->getKeywordList()),
            $this->updateList($baseConfiguration->getAuthorList(), $newConfiguration->getAuthorList()),
            $this->updateList(
                $baseConfiguration->getProvidedPackageList(),
                $newConfiguration->getProvidedPackageList()
            ),
            $this->updateList(
                $baseConfiguration->getSuggestedPackageList(),
                $newConfiguration->getSuggestedPackageList()
            ),
            $this->updateList($baseConfiguration->getSupportList(), $newConfiguration->getSupportList()),
            $this->updateList(
                $baseConfiguration->getRequiredDevPackageList(),
                $newConfiguration->getRequiredDevPackageList()
            ),
            $this->updateList(
                $baseConfiguration->getRequiredDevPackageList(),
                $newConfiguration->getRequiredDevPackageList()
            ),
            $this->updateList($baseConfiguration->getAutoloadList(), $newConfiguration->getAutoloadList()),
            $this->updateList($baseConfiguration->getAutoloadDevList(), $newConfiguration->getAutoloadDevList()),
            $this->updateList($baseConfiguration->getScriptList(), $newConfiguration->getScriptList())
        );
    }

    /**
     * @param object $entity
     *
     * @return null|string
     */
    public function getEntityId($entity)
    {
        switch (true) {
            case $entity instanceof Author:
                $id = $entity->getName();
                break;
            case $entity instanceof SuggestedPackage:
                $id = $entity->getName();
                break;
            case $entity instanceof Support:
                $id = $entity->getType();
                break;
            case $entity instanceof Autoload:
                $id = $entity->getType().'#'.$entity->getNamespace();
                break;
            case $entity instanceof Package:
                $id = $entity->getName();
                break;
            case $entity instanceof Script:
                $id = $entity->getName();
                break;
            default:
                $id = null;
        };

        return $id;
    }

    /**
     * @param mixed $baseValue
     * @param mixed $newValue
     *
     * @return mixed
     */
    protected function updateIfDefined($baseValue, $newValue)
    {
        return $newValue ? $newValue : $baseValue;
    }

    /**
     * @param array $newList
     * @param array $oldList
     *
     * @return array
     */
    protected function mergeValueList(array $newList, array $oldList)
    {
        return array_values(
            array_unique(
                array_merge($oldList, $newList)
            )
        );
    }

    /**
     * @param array $newEntityList
     * @param array $oldEntityList
     *
     * @return array
     */
    protected function updateList(array $newEntityList, array $oldEntityList)
    {
        $list = [];
        $mergedEntityIdList = [];
        $self = $this;
        foreach ($newEntityList as $newEntity) {
            // Search for an old entry
            $newEntityId = $this->getEntityId($newEntity);
            $oldEntityMatches = array_filter(
                $oldEntityList,
                function (Package $oldEntity) use ($newEntityId, $self) {
                    return $self->getEntityId($oldEntity) == $newEntityId;
                }
            );
            if (count($oldEntityMatches)) {
                $mergedEntityIdList[$newEntityId] = true;
            }
            $list[] = $newEntity;
        }
        // Merge remaining entities that have not been already merged
        foreach ($oldEntityList as $entity) {
            if (!array_key_exists($this->getEntityId($entity), $mergedEntityIdList)) {
                $list[] = $entity;
            }
        }

        return $list;
    }
}
