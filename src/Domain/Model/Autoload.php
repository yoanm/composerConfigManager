<?php
namespace Yoanm\InitRepositoryWithComposer\Domain\Model;

class Autoload
{
    const TYPE_PSR0 = 'psr-0';
    const TYPE_PSR4 = 'psr-4';

    /** @var string */
    private $type;
    /** @var AutoloadEntry[] */
    private $entryList = [];

    /**
     * @param string           $type
     * @param AutoloadEntry[]  $entryList
     */
    public function __construct($type, array $entryList)
    {
        $this->type = $type;
        $this->entryList = $entryList;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return AutoloadEntry[]
     */
    public function getEntryList()
    {
        return $this->entryList;
    }
}