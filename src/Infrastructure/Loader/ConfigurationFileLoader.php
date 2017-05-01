<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Loader;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\ComposerConfigManager\Application\Loader\ConfigurationFileLoaderInterface;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\ComposerConfigManager\Infrastructure\Serializer\Encoder\ComposerEncoder;
use Yoanm\ComposerConfigManager\Infrastructure\Writer\ConfigurationFileWriter;

class ConfigurationFileLoader implements ConfigurationFileLoaderInterface
{
    /** @var Finder */
    private $finder;
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(Finder $finder, SerializerInterface $serializer)
    {
        $this->finder = $finder;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function fromPath($path)
    {
        $finder = $this->finder
            ->in($path)
            ->files()
            ->name(ConfigurationFileWriter::FILENAME)
            ->depth(0);

        /** @var SplFileInfo|null $file */
        $file = null;
        foreach ($finder as $result) {
            $file = $result;
            break;
        }

        if (null === $file) {
            throw new FileNotFoundException(
                null,
                0,
                null,
                sprintf(
                    '%s/%s',
                    trim($path, '/'),
                    ConfigurationFileWriter::FILENAME
                )
            );
        }

        return $this->fromString($file->getContents());
    }

    /**
     * {@inheritdoc}
     */
    public function fromString($serializedConfiguration)
    {
        return $this->serializer->deserialize(
            $serializedConfiguration,
            ConfigurationFile::class,
            ComposerEncoder::FORMAT
        );
    }
}
