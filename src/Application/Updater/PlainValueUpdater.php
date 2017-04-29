<?php
namespace Yoanm\ComposerConfigManager\Application\Updater;

class PlainValueUpdater
{
    /**
     * @param string $baseValue
     * @param string $newValue
     *
     * @return string
     */
    public function update($newValue, $baseValue)
    {
        return $newValue ? $newValue : $baseValue;
    }
}
