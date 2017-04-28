<?php
namespace Yoanm\ComposerConfigManager\Application\Updater;

use Yoanm\ComposerConfigManager\Domain\Model\Author;
use Yoanm\ComposerConfigManager\Domain\Model\Autoload;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationItem;
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

    /**
     * @param ConfigurationItem[] $newEntityList
     * @param ConfigurationItem[] $oldEntityList
     *
     * @return ConfigurationItem[]
     */
    protected function updateList(array $newEntityList, array $oldEntityList)
    {
        $existingEntityIdList = [];
        foreach ($newEntityList as $entity) {
            $existingEntityIdList[$entity->getItemId()] = true;
        }
        $normalizedOldEntityList = [];
        foreach ($oldEntityList as $oldEntity) {
            if (!array_key_exists($oldEntity->getItemId(), $existingEntityIdList)) {
                $normalizedOldEntityList[] = $oldEntity;
            } else {
                // A new entity have been defined, loop over new entity list and append all entities with the same id
                $oldEntityId = $oldEntity->getItemId();
                foreach ($newEntityList as $newEntityKey => $newEntity) {
                    if ($newEntity->getItemId() == $oldEntityId) {
                        $normalizedOldEntityList[] = $this->mergeEntity($oldEntity, $newEntity);
                        unset($newEntityList[$newEntityKey]);
                    }
                }
            }
        }

        return array_merge($normalizedOldEntityList, $newEntityList);
    }

    /**
     * @param ConfigurationItem $oldEntity
     * @param ConfigurationItem $newEntity
     *
     * @return ConfigurationItem
     */
    protected function mergeEntity($oldEntity, $newEntity)
    {
        switch (true) {
            case $newEntity instanceof Author && $oldEntity instanceof Author:
                return new Author(
                    $newEntity->getName(),
                    $newEntity->getEmail() ? $newEntity->getEmail() : $oldEntity->getEmail(),
                    $newEntity->getRole() ? $newEntity->getRole() : $oldEntity->getRole()
                );
                break;
        }

        return $newEntity;
    }
}
