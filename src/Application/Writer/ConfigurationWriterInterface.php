<?php
namespace Yoanm\InitRepositoryWithComposer\Application\Writer;

use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;

interface ConfigurationWriterInterface
{
    const FILENAME = 'composer.json';

    /**
     * @param Configuration $configuration
     * @param string        $destinationPath
     */
    public function write(Configuration $configuration, $destinationPath);
}
