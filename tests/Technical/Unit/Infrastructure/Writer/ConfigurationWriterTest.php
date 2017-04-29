<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Infrastructure\Writer;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Infrastructure\Writer\ConfigurationWriter;

class ConfigurationWriterTest extends \PHPUnit_Framework_TestCase
{
    /** @var SerializerInterface|ObjectProphecy */
    private $serializer;
    /** @var Filesystem|ObjectProphecy */
    private $filesystem;
    /** @var ConfigurationWriter */
    private $writer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->serializer = $this->prophesize(SerializerInterface::class);
        $this->filesystem = $this->prophesize(Filesystem::class);
        $this->writer = new ConfigurationWriter(
            $this->serializer->reveal(),
            $this->filesystem->reveal()
        );
    }

    public function testWrite()
    {
        $serializedData = 'serialized_data';
        $destinationPath = 'dest_path';

        $filename = sprintf(
            '%s%s%s',
            $destinationPath,
            DIRECTORY_SEPARATOR,
            ConfigurationWriter::FILENAME
        );

        /** @var Configuration|ObjectProphecy $configuration */
        $configuration = $this->prophesize(Configuration::class);

        $data = $this->serializer->serialize($configuration->reveal(), 'composer')
            ->willReturn($serializedData)
            ->shouldBeCalled();

        $this->filesystem->dumpFile($filename, $serializedData)
            ->shouldBeCalled();

        $this->writer->write($configuration->reveal(), $destinationPath);
    }
}
