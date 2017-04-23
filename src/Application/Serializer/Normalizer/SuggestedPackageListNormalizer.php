<?php
namespace Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer;

use Yoanm\InitRepositoryWithComposer\Domain\Model\SuggestedPackage;

class SuggestedPackageListNormalizer
{
    /**
     * @param SuggestedPackage[] $suggestedPackageList
     *
     * @return array
     */
    public function normalize(array $suggestedPackageList)
    {
        $normalizeList = [];
        foreach ($suggestedPackageList as $package) {
            $normalizeList[$package->getName()] = $package->getDescription();
        }

        return $normalizeList;
    }
}