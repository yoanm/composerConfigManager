<?php
namespace Yoanm\ComposerConfigManager\Application;

use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class CreateConfigurationRequest
{
    /** @var string */
    private $destinationFolder;
    /** @var Configuration */
    private $configuration;
    /** @var Configuration */
    private $templateConfiguration;

    /**
     * @param Configuration $configuration
     * @param string        $destinationFolder
     */
    public function __construct(
        Configuration $configuration,
        $destinationFolder,
        Configuration $templateConfiguration = null
    ) {
        $this->destinationFolder = $destinationFolder;
        $this->configuration = $configuration;
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
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return Configuration
     */
    public function getTemplateConfiguration()
    {
        return $this->templateConfiguration;
    }
}
