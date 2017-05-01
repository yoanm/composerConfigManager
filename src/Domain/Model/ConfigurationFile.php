<?php
namespace Yoanm\ComposerConfigManager\Domain\Model;

class ConfigurationFile
{
    /** @var Configuration */
    private $configuration;
    /** @var string[] */
    private $keyList = [];

    /**
     * @param Configuration $configuration
     * @param string[]      $keyList
     */
    public function __construct(Configuration $configuration, array $keyList)
    {
        $this->configuration = $configuration;
        $this->keyList = $keyList;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return \string[]
     */
    public function getKeyList()
    {
        return $this->keyList;
    }
}
