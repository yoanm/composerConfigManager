<?php
namespace Yoanm\ComposerConfigManager\Application;

use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class UpdateConfigurationRequest
{
    /** @var string */
    private $destinationFolder;
    /** @var Configuration[] */
    private $configurationList;

    /**
     * @param Configuration[] $configurationList
     * @param string          $destinationFolder
     */
    public function __construct(array $configurationList, $destinationFolder)
    {
        $this->destinationFolder = $destinationFolder;
        $this->configurationList = $configurationList;
    }

    /**
     * @return string
     */
    public function getDestinationFolder()
    {
        return $this->destinationFolder;
    }

    /**
     * @return Configuration[]
     */
    public function getConfigurationList()
    {
        return $this->configurationList;
    }
}
