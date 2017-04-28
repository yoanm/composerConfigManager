<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

interface DenormalizerInterface
{
    /**
     * @param array $list
     *
     * @return mixed
     */
    public function denormalize(array $list);
}
