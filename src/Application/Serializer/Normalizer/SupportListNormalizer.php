<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Yoanm\ComposerConfigManager\Domain\Model\Support;

class SupportListNormalizer
{
    /**
     * @param Support[] $supportList
     *
     * @return array
     */
    public function normalize(array $supportList)
    {
        $normalizedList = [];
        foreach ($supportList as $support) {
            $normalizedList[$support->getType()] = $support->getUrl();
        }

        return $normalizedList;
    }

    /**
     * @param array $supportList
     *
     * @return Support[]
     */
    public function denormalize(array $supportList)
    {
        $denormalizedList = [];
        foreach ($supportList as $supportType => $supportUrl) {
            $denormalizedList[] = new Support($supportType, $supportUrl);
        }

        return $denormalizedList;
    }
}
