<?php
namespace Yoanm\ComposerConfigManager\Application;

use Yoanm\ComposerConfigManager\Application\Updater\ConfigurationUpdater;
use Yoanm\ComposerConfigManager\Application\Writer\ConfigurationWriterInterface;

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
    public function run(UpdateConfigurationRequest $request)
    {
        $this->configurationWriter->write(
            $this->configurationUpdater->update(
                $request->getBaseConfiguration(),
                $request->getNewConfiguration()
            ),
            $request->getDestinationFolder()
        );
    }
}
