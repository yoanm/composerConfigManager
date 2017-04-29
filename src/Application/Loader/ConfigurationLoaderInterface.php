<?php
namespace Yoanm\ComposerConfigManager\Application\Loader;

use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

interface ConfigurationLoaderInterface
{
    /**
     * @param string $path
     *
     * @return Configuration
     */
    public function fromPath($path);
}
