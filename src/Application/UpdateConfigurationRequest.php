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
    /** @var Configuration */
    private $templateConfiguration;

    /**
     * @param Configuration      $baseConfiguration
     * @param Configuration      $newConfiguration
     * @param string             $destinationFolder
     * @param Configuration|null $templateConfiguration
     */
    public function __construct(
        Configuration $baseConfiguration,
        Configuration $newConfiguration,
        $destinationFolder,
        Configuration $templateConfiguration = null
    ) {
        $this->baseConfiguration = $baseConfiguration;
        $this->newConfiguration = $newConfiguration;
        $this->destinationFolder = $destinationFolder;
        $this->templateConfiguration = $templateConfiguration;
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

    /**
     * @return Configuration
     */
    public function getTemplateConfiguration()
    {
        return $this->templateConfiguration;
    }
}
