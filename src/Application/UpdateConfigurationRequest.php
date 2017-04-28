<?php
namespace Yoanm\ComposerConfigManager\Application;

use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class UpdateConfigurationRequest
{
    /** @var string */
    private $destinationFolder;
    /** @var Configuration */
    private $newConfiguration;
    /** @var Configuration */
    private $baseConfiguration;

    /**
     * @param Configuration $baseConfiguration
     * @param Configuration $newConfiguration
     * @param string        $destinationFolder
     */
    public function __construct(
        Configuration $baseConfiguration,
        Configuration $newConfiguration,
        $destinationFolder
    ) {
        $this->baseConfiguration = $baseConfiguration;
        $this->newConfiguration = $newConfiguration;
        $this->destinationFolder = $destinationFolder;
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
    public function getNewConfiguration()
    {
        return $this->newConfiguration;
    }

    /**
     * @return Configuration
     */
    public function getBaseConfiguration()
    {
        return $this->baseConfiguration;
    }
}
