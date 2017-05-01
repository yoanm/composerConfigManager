<?php
namespace Yoanm\ComposerConfigManager\Application\Writer;

use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

interface ConfigurationFileWriterInterface
{
    /**
     * @param ConfigurationFile $configurationFile
     * @param string            $destinationPath
     */
    public function write(ConfigurationFile $configurationFile, $destinationPath);
}
