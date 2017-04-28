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
            $this->updateIfDefined($newConfiguration->getPackageName(), $baseConfiguration->getPackageName()),
            $this->updateIfDefined($newConfiguration->getType(), $baseConfiguration->getType()),
            $this->updateIfDefined($newConfiguration->getLicense(), $baseConfiguration->getLicense()),
            $this->updateIfDefined($newConfiguration->getPackageVersion(), $baseConfiguration->getPackageVersion()),
            $this->updateIfDefined($newConfiguration->getDescription(), $baseConfiguration->getDescription()),
            $this->mergeValueList($newConfiguration->getKeywordList(), $baseConfiguration->getKeywordList()),
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
            $this->updateScriptList($newConfiguration->getScriptList(), $baseConfiguration->getScriptList())
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
    protected function updateIfDefined($newValue, $baseValue)
    {
        return $newValue ? $newValue : $baseValue;
    }

    /**
     * @param array $newList
     * @param array $oldList
     *
     * @return array
     */
    protected function mergeValueList(array $oldList, array $newList)
    {
        return array_values(
            array_unique(
                array_merge($newList, $oldList)
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
        $listTmp = [];
        $self = $this;
        foreach ($newEntityList as $newEntity) {
            // Search for an old entry
            $newEntityId = $this->getEntityId($newEntity);
            $oldEntityMatches = array_filter(
                $oldEntityList,
                function ($oldEntity) use ($newEntityId, $self) {
                    return $self->getEntityId($oldEntity) == $newEntityId;
                }
            );
            if (count($oldEntityMatches)) {
                $oldEntity = array_shift($oldEntityMatches);
                $newEntity = $this->mergeEntity($oldEntity, $newEntity);
                $oldEntityList = array_map(
                    function ($oldEntity) use ($newEntityId, $newEntity, $self) {
                        return $self->getEntityId($oldEntity) == $newEntityId
                            ? $newEntity
                            : $oldEntity
                        ;
                    },
                    $oldEntityList
                );
            } else {
                $listTmp[] = $newEntity;
            }
        }

        // Merge remaining entities that have not been already merged
        $list = [];
        foreach (array_reverse($oldEntityList, true) as $entity) {
            array_unshift($list, $entity);
        }

        return array_merge($list, $listTmp);
    }

    /**
     * @param array $newEntityList
     * @param array $oldEntityList
     *
     * @return array
     */
    protected function updateScriptList(array $newEntityList, array $oldEntityList)
    {
        $mergedEntityIdList = [];
        $self = $this;
        $normalizedNewEntityList = [];
        foreach ($newEntityList as $newEntity) {
            // Search for an old entry
            $newEntityId = $this->getEntityId($newEntity);
            $oldEntityMatches = array_filter(
                $oldEntityList,
                function ($oldEntity) use ($newEntityId, $self) {
                    return $self->getEntityId($oldEntity) == $newEntityId;
                }
            );
            if (count($oldEntityMatches)) {
                $mergedEntityIdList[$newEntityId] = true;
            }
            $normalizedNewEntityList[] = $newEntity;
        }
        // Merge remaining entities that have not been already merged
        $normalizedOldEntityList = [];
        foreach ($oldEntityList as $oldEntity) {
            if (!array_key_exists($this->getEntityId($oldEntity), $mergedEntityIdList)) {
                $normalizedOldEntityList[] = $oldEntity;
            } else {
                $oldEntityId = $this->getEntityId($oldEntity);
                foreach ($normalizedNewEntityList as $newEntityKey => $newEntity) {
                    if ($self->getEntityId($newEntity) == $oldEntityId) {
                        $normalizedOldEntityList[] = $newEntity;
                        unset($normalizedNewEntityList[$newEntityKey]);
                    }
                }
            }
        }

        return array_merge($normalizedOldEntityList, $normalizedNewEntityList);
    }

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
