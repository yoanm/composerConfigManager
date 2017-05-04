<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Infrastructure\Writer;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\ComposerConfigManager\Infrastructure\Writer\ConfigurationFileWriter;

/**
 * @covers Yoanm\ComposerConfigManager\Infrastructure\Writer\ConfigurationFileWriter
 */
class ConfigurationFileWriterTest extends \PHPUnit_Framework_TestCase
{
    /** @var SerializerInterface|ObjectProphecy */
    private $serializer;
    /** @var Filesystem|ObjectProphecy */
    private $filesystem;
    /** @var ConfigurationFileWriter */
    private $writer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->serializer = $this->prophesize(SerializerInterface::class);
        $this->filesystem = $this->prophesize(Filesystem::class);
        $this->writer = new ConfigurationFileWriter(
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
            ConfigurationFileWriter::FILENAME
        );

        /** @var ConfigurationFile|ObjectProphecy $configurationFile */
        $configurationFile = $this->prophesize(ConfigurationFile::class);

        $data = $this->serializer->serialize($configurationFile->reveal(), 'composer')
            ->willReturn($serializedData)
            ->shouldBeCalled();

        $this->filesystem->dumpFile($filename, $serializedData)
            ->shouldBeCalled();

        $this->writer->write($configurationFile->reveal(), $destinationPath);
    }
}
