<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\UpdateConfiguration;
use Yoanm\ComposerConfigManager\Application\UpdateConfigurationRequest;
use Yoanm\ComposerConfigManager\Application\Updater\ConfigurationUpdater;
use Yoanm\ComposerConfigManager\Application\Writer\ConfigurationWriterInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Infrastructure\Writer\ConfigurationWriter;

class UpdateConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigurationWriterInterface|ObjectProphecy */
    private $configurationWriter;
    /** @var ConfigurationUpdater|ObjectProphecy */
    private $configurationUpdater;
    /** @var UpdateConfiguration */
    private $updater;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->configurationWriter = $this->prophesize(ConfigurationWriter::class);
        $this->configurationUpdater = $this->prophesize(ConfigurationUpdater::class);

        $this->updater = new UpdateConfiguration(
            $this->configurationWriter->reveal(),
            $this->configurationUpdater->reveal()
        );
    }

    public function testRun()
    {
        $destPath = 'path';
        /** @var Configuration|ObjectProphecy $baseConfiguration */
        $baseConfiguration = $this->prophesize(Configuration::class);
        /** @var Configuration|ObjectProphecy $newConfiguration */
        $newConfiguration = $this->prophesize(Configuration::class);
        /** @var Configuration|ObjectProphecy $updatedConfiguration */
        $updatedConfiguration = $this->prophesize(Configuration::class);
        /** @var UpdateConfigurationRequest|ObjectProphecy $request */
        $request = $this->prophesize(UpdateConfigurationRequest::class);

        $request->getBaseConfiguration()
            ->willReturn($baseConfiguration->reveal())
            ->shouldBeCalled();
        $request->getNewConfiguration()
            ->willReturn($newConfiguration->reveal())
            ->shouldBeCalled();
        $request->getDestinationFolder()
            ->willReturn($destPath)
            ->shouldBeCalled();
        $request->getTemplateConfiguration()
            ->willReturn(null)
            ->shouldBeCalled();

        $this->configurationUpdater->update($baseConfiguration->reveal(), $newConfiguration->reveal())
            ->willReturn($updatedConfiguration->reveal())
            ->shouldBeCalled();
        $this->configurationWriter->write($updatedConfiguration->reveal(), $destPath)
            ->shouldBeCalled();

        $this->updater->run($request->reveal());
    }

    public function testRunWithTemplate()
    {
        $destPath = 'path';
        /** @var Configuration|ObjectProphecy $baseConfiguration */
        $baseConfiguration = $this->prophesize(Configuration::class);
        /** @var Configuration|ObjectProphecy $newConfiguration */
        $newConfiguration = $this->prophesize(Configuration::class);
        /** @var Configuration|ObjectProphecy $updatedConfiguration */
        $updatedConfiguration = $this->prophesize(Configuration::class);
        /** @var UpdateConfigurationRequest|ObjectProphecy $request */
        $request = $this->prophesize(UpdateConfigurationRequest::class);
        /** @var Configuration|ObjectProphecy $templateConfiguration */
        $templateConfiguration = $this->prophesize(Configuration::class);
        /** @var Configuration|ObjectProphecy $lastUpdateConfiguration */
        $lastUpdateConfiguration = $this->prophesize(Configuration::class);

        $request->getBaseConfiguration()
            ->willReturn($baseConfiguration->reveal())
            ->shouldBeCalled();
        $request->getNewConfiguration()
            ->willReturn($newConfiguration->reveal())
            ->shouldBeCalled();
        $request->getDestinationFolder()
            ->willReturn($destPath)
            ->shouldBeCalled();
        $request->getTemplateConfiguration()
            ->willReturn($templateConfiguration)
            ->shouldBeCalled();

        $this->configurationUpdater->update($baseConfiguration->reveal(), $newConfiguration->reveal())
            ->willReturn($updatedConfiguration->reveal())
            ->shouldBeCalled();
        $this->configurationUpdater->update($templateConfiguration->reveal(), $updatedConfiguration->reveal())
            ->willReturn($lastUpdateConfiguration->reveal())
            ->shouldBeCalled();
        $this->configurationWriter->write($lastUpdateConfiguration->reveal(), $destPath)
            ->shouldBeCalled();

        $this->updater->run($request->reveal());
    }
}
