<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Writer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\ComposerConfigManager\Application\Writer\ConfigurationWriterInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class ConfigurationWriter implements ConfigurationWriterInterface
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
    public function write(Configuration $configuration, $destinationPath)
    {
        $data = $this->serializer->serialize($configuration, 'composer');

        $filename = sprintf(
            '%s%s%s',
            trim($destinationPath, DIRECTORY_SEPARATOR),
            DIRECTORY_SEPARATOR,
            self::FILENAME
        );

        var_dump("WRITE $filename");

        $this->filesystem->dumpFile($filename, $data);
    }
}
