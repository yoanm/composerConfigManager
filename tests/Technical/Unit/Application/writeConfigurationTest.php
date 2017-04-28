<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\WriteConfiguration;
use Yoanm\ComposerConfigManager\Application\WriteConfigurationRequest;
use Yoanm\ComposerConfigManager\Application\Writer\ConfigurationWriterInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class WriteConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigurationWriterInterface|ObjectProphecy */
    private $configurationWriter;
    /** @var WriteConfiguration */
    private $writer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->configurationWriter = $this->prophesize(ConfigurationWriterInterface::class);
        $this->writer = new WriteConfiguration(
            $this->configurationWriter->reveal()
        );
    }


    public function testRun()
    {
        $destFolder = 'folder';

        /** @var WriteConfigurationRequest|ObjectProphecy $request */
        $request = $this->prophesize(WriteConfigurationRequest::class);
        /** @var Configuration|ObjectProphecy $configuration */
        $configuration = $this->prophesize(Configuration::class);

        $request->getConfiguration()
            ->willReturn($configuration->reveal())
            ->shouldBeCalled();
        $request->getDestinationFolder()
            ->willReturn($destFolder)
            ->shouldBeCalled();

        $this->configurationWriter->write($configuration->reveal(), $destFolder)
            ->shouldBeCalled();

        $this->writer->run($request->reveal());
    }
}
