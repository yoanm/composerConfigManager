<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

interface DenormalizerInterface
{
    /**
     * @param array $list
     *
     * @return array
     */
    public function denormalize(array $list);
}
