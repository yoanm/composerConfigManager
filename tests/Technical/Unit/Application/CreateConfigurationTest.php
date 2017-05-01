<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\CreateConfiguration;
use Yoanm\ComposerConfigManager\Application\CreateConfigurationRequest;
use Yoanm\ComposerConfigManager\Application\Updater\ConfigurationUpdater;
use Yoanm\ComposerConfigManager\Application\Writer\ConfigurationWriterInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class CreateConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigurationWriterInterface|ObjectProphecy */
    private $configurationWriter;
    /** @var ConfigurationUpdater|ObjectProphecy */
    private $configurationUpdater;
    /** @var CreateConfiguration */
    private $creator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->configurationWriter = $this->prophesize(ConfigurationWriterInterface::class);
        $this->configurationUpdater = $this->prophesize(ConfigurationUpdater::class);
        $this->creator = new CreateConfiguration(
            $this->configurationWriter->reveal(),
            $this->configurationUpdater->reveal()
        );
    }


    public function testRun()
    {
        $destFolder = 'folder';

        /** @var CreateConfigurationRequest|ObjectProphecy $request */
        $request = $this->prophesize(CreateConfigurationRequest::class);
        /** @var Configuration|ObjectProphecy $configuration */
        $configuration = $this->prophesize(Configuration::class);

        $request->getConfiguration()
            ->willReturn($configuration->reveal())
            ->shouldBeCalled();
        $request->getTemplateConfiguration()
            ->willReturn(null)
            ->shouldBeCalled();
        $request->getDestinationFolder()
            ->willReturn($destFolder)
            ->shouldBeCalled();

        $this->configurationWriter->write($configuration->reveal(), $destFolder)
            ->shouldBeCalled();

        $this->creator->run($request->reveal());
    }
}
