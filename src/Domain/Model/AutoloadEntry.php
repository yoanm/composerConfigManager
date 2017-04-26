<?php
namespace Yoanm\InitRepositoryWithComposer\Domain\Model;

class AutoloadEntry
{
    /** @var string */
    private $namespace;
    /** @var string */
    private $path;

    /**
     * @param string $namespace
     * @param string $path
     */
    public function __construct($namespace, $path)
    {
        $this->namespace = $namespace;
        $this->path = $path;
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
