<?php
namespace Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer;

use Yoanm\InitRepositoryWithComposer\Domain\Model\Package;

class PackageListNormalizer
{
    /**
     * @param Package[] $packageList
     *
     * @return array
     */
    public function normalize(array $packageList)
    {
        $normalizeList = [];
        foreach ($packageList as $package) {
            $normalizeList[$package->getName()] = $package->getVersionConstraint();
        }

        return $normalizeList;
    }
}