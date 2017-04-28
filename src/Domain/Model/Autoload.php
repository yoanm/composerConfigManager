<?php
namespace Yoanm\ComposerConfigManager\Domain\Model;

class Autoload
{
    const TYPE_PSR0 = 'psr-0';
    const TYPE_PSR4 = 'psr-4';

    /** @var string */
    private $type;
    /** @var string */
    private $namespace;
    /** @var string */
    private $path;

    /**
     * @param string $type
     * @param string $path
     * @param string $namespace
     */
    public function __construct($type, $path, $namespace)
    {
        $this->type = $type;
        $this->namespace = $namespace;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
