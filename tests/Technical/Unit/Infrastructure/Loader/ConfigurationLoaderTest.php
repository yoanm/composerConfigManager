<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Loader;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Infrastructure\Serializer\Encoder\ComposerEncoder;
use Yoanm\ComposerConfigManager\Infrastructure\Writer\ConfigurationWriter;

class ConfigurationLoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var Finder|ObjectProphecy */
    private $finder;
    /** @var SerializerInterface|ObjectProphecy */
    private $serializer;
    /** @var ConfigurationLoader */
    private $loader;

    public function setUp()
    {
        $this->finder = $this->prophesize(Finder::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->loader = new ConfigurationLoader(
            $this->finder->reveal(),
            $this->serializer->reveal()
        );
    }

    public function testFromPath()
    {
        $expectedLoadedContent = 'loaded_content';
        $fileContent = 'content';
        $path = 'path';
        /** @var SplFileInfo|ObjectProphecy $file */
        $file = $this->prophesize(SplFileInfo::class);

        $this->finder->in($path)
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->files()
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->name(ConfigurationWriter::FILENAME)
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->depth(0)
            ->willReturn([$file->reveal()]) // finder is also an iterator but it's easier to manage it like that
            ->shouldBeCalled();
        $file->getContents()
            ->willReturn($fileContent)
            ->shouldBeCalled();


        $this->serializer->deserialize($fileContent, Configuration::class, ComposerEncoder::FORMAT)
            ->willReturn($expectedLoadedContent)
            ->shouldBeCalled();

        $this->assertSame(
            $expectedLoadedContent,
            $this->loader->fromPath($path)
        );
    }

    public function testFromPathThrowExceptionIfFileNotFound()
    {
        $path = 'path';

        $this->finder->in($path)
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->files()
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->name(ConfigurationWriter::FILENAME)
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->depth(0)
            ->willReturn([])
            ->shouldBeCalled();

        $this->setExpectedException(
            FileNotFoundException::class,
            sprintf(
                '%s/%s',
                $path,
                ConfigurationWriter::FILENAME
            )
        );

        $this->loader->fromPath($path);
    }

    public function testFromString()
    {
        $expectedLoadedContent = 'loaded_content';
        $fileContent = 'content';;

        $this->serializer->deserialize($fileContent, Configuration::class, ComposerEncoder::FORMAT)
            ->willReturn($expectedLoadedContent)
            ->shouldBeCalled();

        $this->assertSame(
            $expectedLoadedContent,
            $this->loader->fromString($fileContent)
        );
    }

}
