<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Yoanm\ComposerConfigManager\Domain\Model\Autoload;

class AutoloadListNormalizer
{
    /**
     * @param Autoload[] $autoloadList
     *
     * @return array
     */
    public function normalize(array $autoloadList)
    {
        $normalizeList = [];
        foreach ($autoloadList as $autoload) {
            if (count($autoload->getEntryList())) {
                foreach ($autoload->getEntryList() as $entry) {
                    $normalizeList[$autoload->getType()][$entry->getNamespace()] = $entry->getPath();
                }
            }
        }

        return $normalizeList;
    }
}
