<?php
namespace Yoanm\ComposerConfigManager\Application;

use Yoanm\ComposerConfigManager\Application\Writer\ConfigurationWriterInterface;

class CreateConfiguration
{
    /** @var ConfigurationWriterInterface */
    private $configurationWriter;

    /**
     * @param ConfigurationWriterInterface $configurationWriter
     */
    public function __construct(ConfigurationWriterInterface $configurationWriter)
    {
        $this->configurationWriter = $configurationWriter;
    }
    public function run(CreateConfigurationRequest $request)
    {
        $this->configurationWriter->write($request->getConfiguration(), $request->getDestinationFolder());
    }
}
