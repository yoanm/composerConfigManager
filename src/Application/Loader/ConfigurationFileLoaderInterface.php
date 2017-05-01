<?php
namespace Yoanm\ComposerConfigManager\Application\Loader;

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
