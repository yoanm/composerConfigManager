<?php
namespace Yoanm\ComposerConfigManager\Application;

use Yoanm\ComposerConfigManager\Application\Updater\ConfigurationUpdater;
use Yoanm\ComposerConfigManager\Application\Writer\ConfigurationWriterInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class UpdateConfiguration
{
    /** @var ConfigurationWriterInterface */
    private $configurationWriter;
    /** @var ConfigurationUpdater */
    private $configurationUpdater;

    /**
     * @param ConfigurationWriterInterface $configurationWriter
     * @param ConfigurationUpdater         $configurationUpdater
     */
    public function __construct(
        ConfigurationWriterInterface $configurationWriter,
        ConfigurationUpdater $configurationUpdater
    ) {
        $this->configurationWriter = $configurationWriter;
        $this->configurationUpdater = $configurationUpdater;
    }

    /**
     * @param UpdateConfigurationRequest $request
     */
    public function run(UpdateConfigurationRequest $request)
    {
        $this->configurationWriter->write(
            $this->getConfiguration($request),
            $request->getDestinationFolder()
        );
    }

    /**
     * @param UpdateConfigurationRequest $request
     *
     * @return Configuration
     */
    protected function getConfiguration(UpdateConfigurationRequest $request)
    {
        return $this->configurationUpdater->update($request->getConfigurationList());
    }
}
