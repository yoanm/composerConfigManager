<?php
namespace Yoanm\ComposerConfigManager\Application\Request;

use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

class UpdateConfigurationFileListRequest
{
    /** @var string */
    private $destinationFolder;
    /** @var ConfigurationFile[] */
    private $configurationList;

    /**
     * @param ConfigurationFile[] $configurationList
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
     * @return ConfigurationFile[]
     */
    public function getConfigurationFileList()
    {
        return $this->configurationList;
    }
}
