<?php
namespace Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer;

use Yoanm\InitRepositoryWithComposer\Domain\Model\Support;

class SupportListNormalizer
{
    /**
     * @param Support[] $supportList
     *
     * @return array
     */
    public function normalize(array $supportList)
    {
        $normalizeList = [];
        foreach ($supportList as $support) {
            $normalizeList[$support->getType()] = $support->getUrl();
        }

        return $normalizeList;
    }
}
