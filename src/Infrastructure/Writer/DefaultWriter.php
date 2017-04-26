<?php
namespace Yoanm\InitRepositoryWithComposer\Infrastructure\Writer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\InitRepositoryWithComposer\Application\Writer\ConfigurationWriterInterface;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;

class DefaultWriter implements ConfigurationWriterInterface
{
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

        $this->filesystem->dumpFile($filename, $data);
    }
}
