<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Loader;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\ComposerConfigManager\Application\Loader\ConfigurationLoaderInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Infrastructure\Serializer\Encoder\ComposerEncoder;
use Yoanm\ComposerConfigManager\Infrastructure\Writer\ConfigurationWriter;

class ConfigurationLoader implements ConfigurationLoaderInterface
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
        /** @var SplFileInfo|null $file */
        $file = null;
        $finder = $this->finder
            ->in($path)
            ->files()
            ->name(ConfigurationWriter::FILENAME)
            ->depth(0);

        foreach ($finder as $match) {
            $file = $match;
        }

        if (null === $file) {
            throw new FileNotFoundException(
                null,
                0,
                null,
                sprintf(
                    'File %s not found in %s',
                    ConfigurationWriter::FILENAME,
                    $path
                )
            );
        }

        return $this->serializer->deserialize($file->getContents(), Configuration::class, ComposerEncoder::FORMAT);
    }
}
