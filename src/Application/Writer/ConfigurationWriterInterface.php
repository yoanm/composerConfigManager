<?php
namespace Yoanm\ComposerConfigManager\Application\Writer;

use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

interface ConfigurationWriterInterface
{
    const FILENAME = 'composer.json';

    /**
     * @param Configuration $configuration
     * @param string        $destinationPath
     */
    public function write(Configuration $configuration, $destinationPath);
}
