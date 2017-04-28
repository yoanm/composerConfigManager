<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Yoanm\ComposerConfigManager\Domain\Model\Script;

class ScriptListNormalizer
{
    /**
     * @param Script[] $scriptList
     *
     * @return array
     */
    public function normalize(array $scriptList)
    {
        $normalizeList = [];
        foreach ($scriptList as $script) {
            $normalizeList[$script->getName()][] = $script->getCommand();
        }

        return $normalizeList;
    }
}
