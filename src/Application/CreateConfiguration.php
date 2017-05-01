<?php
namespace Yoanm\ComposerConfigManager\Application;

use Yoanm\ComposerConfigManager\Application\Updater\ConfigurationUpdater;
use Yoanm\ComposerConfigManager\Application\Writer\ConfigurationWriterInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class CreateConfiguration
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
     * @param CreateConfigurationRequest $request
     */
    public function run(CreateConfigurationRequest $request)
    {
        $configuration = $request->getConfiguration();
        if ($request->getTemplateConfiguration() instanceof Configuration) {
            $configuration = $this->configurationUpdater->update(
                $request->getTemplateConfiguration(),
                $configuration
            );
        }
        $this->configurationWriter->write($configuration, $request->getDestinationFolder());
    }
}
