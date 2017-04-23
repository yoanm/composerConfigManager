<?php
namespace Yoanm\InitRepositoryWithComposer\Domain\Model;

class Package
{
    /** @var string */
    private $name;
    /** @var string */
    private $versionConstraint;
    /**
     * @param string $name
     * @param string $versionConstraint
     */
    public function __construct($name, $versionConstraint)
    {
        $this->name = $name;
        $this->versionConstraint = $versionConstraint;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersionConstraint()
    {
        return $this->versionConstraint;
    }
}