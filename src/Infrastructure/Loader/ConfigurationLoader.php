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
        $finder = $this->finder
            ->in($path)
            ->files()
            ->name(ConfigurationWriter::FILENAME)
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
                    ConfigurationWriter::FILENAME
                )
            );
        }

        return $this->deserialize($file->getContents());
    }

    /**
     * {@inheritdoc}
     */
    public function fromFilePath($filePath)
    {
        if (!is_file($filePath)) {
            throw new FileNotFoundException(
                null,
                0,
                null,
                $filePath
            );
        }
        return $this->deserialize(file_get_contents($filePath));
    }

    /**
     * @param $serializedConfiguration
     *
     * @return Configuration
     */
    protected function deserialize($serializedConfiguration)
    {
        return $this->serializer->deserialize($serializedConfiguration, Configuration::class, ComposerEncoder::FORMAT);
    }
}
