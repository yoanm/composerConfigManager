<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Writer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\ComposerConfigManager\Application\Writer\ConfigurationFileWriterInterface;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationFileWriter implements ConfigurationFileWriterInterface
{
    const FILENAME = 'composer.json';

    /** @var SerializerInterface */
    private $serializer;
    /** @var Filesystem */
    private $filesystem;

    /**
     * @param SerializerInterface $serializer
     * @param Filesystem          $filesystem
     */
    public function __construct(SerializerInterface $serializer, Filesystem $filesystem)
    {
        $this->serializer = $serializer;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function write(ConfigurationFile $configurationFile, $destinationPath)
    {
        $data = $this->serializer->serialize($configurationFile, 'composer');

        $filename = sprintf(
            '%s%s%s',
            trim($destinationPath, DIRECTORY_SEPARATOR),
            DIRECTORY_SEPARATOR,
            self::FILENAME
        );

        $this->filesystem->dumpFile($filename, $data);
    }
}
