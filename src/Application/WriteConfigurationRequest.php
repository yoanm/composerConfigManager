<?php
namespace Yoanm\InitRepositoryWithComposer\Application;

use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;

class WriteConfigurationRequest
{
    /** @var string */
    private $destinationFolder;
    /** @var Configuration */
    private $configuration;

    /**
     * @param Configuration $configuration
     * @param string        $destinationFolder
     */
    public function __construct(Configuration $configuration, $destinationFolder)
    {
        $this->destinationFolder = $destinationFolder;
        $this->configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getDestinationFolder()
    {
        return $this->destinationFolder;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}