<?php
namespace Yoanm\ComposerConfigManager\Domain\Model;

class SuggestedPackage implements ConfigurationItem
{
    /** @var string */
    private $name;
    /** @var string */
    private $description;

    /**
     * @param string $name
     * @param string $description
     */
    public function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemId()
    {
        return $this->getName();
    }
}
