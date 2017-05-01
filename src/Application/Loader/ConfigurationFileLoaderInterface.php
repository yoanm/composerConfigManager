<?php
namespace Yoanm\ComposerConfigManager\Application\Loader;

use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

interface ConfigurationFileLoaderInterface
{
    /**
     * @param string $path
     *
     * @return ConfigurationFile
     */
    public function fromPath($path);

    /**
     * @param string $serializedConfiguration
     *
     * @return ConfigurationFile
     */
    public function fromString($serializedConfiguration);
}
