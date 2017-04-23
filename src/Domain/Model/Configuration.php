<?php
namespace Yoanm\InitRepositoryWithComposer\Domain\Model;

class Configuration {
    const DEFAULT_TYPE = 'library';
    const DEFAULT_LICENSE = 'MIT';
    const DEFAULT_VERSION = '0.0.1';

    /** @var string */
    private $packageName;
    /** @var string */
    private $type;
    /** @var string */
    private $license;
    /** @var string */
    private $packageVersion;
    /** @var string */
    private $description;
    /** @var string[] */
    private $keywordList = [];
    /** @var Author[] */
    private $authorList = [];
    /** @var Package[] */
    private $providedPackageList = [];
    /** @var SuggestedPackage[] */
    private $suggestedPackageList = [];
    /** @var Support[] */
    private $supportList = [];
    /** @var Autoload[] */
    private $autoloadList = [];
    /** @var Autoload[] */
    private $autoloadDevList = [];
    /** @var Package[] */
    private $requiredPackageList = [];
    /** @var Package[] */
    private $requiredDevPackageList = [];
    /** @var Script[] */
    private $scriptList = [];

    /**
     * @param string $packageName
     * @param string $type
     * @param string $description
     * @param string $license
     * @param string $packageVersion
     */
    public function __construct(
        $packageName,
        $type = null,
        $description = null,
        $license = null,
        $packageVersion = null
    ) {
        $this->packageName = $packageName;
        $this->type = null === $type ? self::DEFAULT_TYPE : $type;
        $this->description = $description;
        $this->license = null === $license ? self::DEFAULT_LICENSE : $license;
        $this->packageVersion = null === $packageVersion ? self::DEFAULT_VERSION : $packageVersion;
    }

    /**
     * @param string $keyword
     */
    public function addKeyword($keyword)
    {
        $this->keywordList[] = $keyword;
    }

    /**
     * @param Author $author
     */
    public function addAuthor(Author $author)
    {
        $this->authorList[] = $author;
    }

    /**
     * @param Package $providedPackage
     */
    public function addProvidedPackage(Package $providedPackage)
    {
        $this->providedPackageList[] = $providedPackage;
    }

    /**
     * @param SuggestedPackage $suggestedPackage
     */
    public function addSuggestedPackage(SuggestedPackage $suggestedPackage)
    {
        $this->suggestedPackageList[] = $suggestedPackage;
    }

    /**
     * @param Support $support
     */
    public function addSupport(Support $support)
    {
        $this->supportList[] = $support;
    }

    /**
     * @param Autoload $autoload
     */
    public function addAutoload(Autoload $autoload)
    {
        $this->autoloadList[] = $autoload;
    }

    /**
     * @param Autoload $autoloadDev
     */
    public function addAutoloadDev(Autoload $autoloadDev)
    {
        $this->autoloadDevList[] = $autoloadDev;
    }

    /**
     * @param Package $requiredPackage
     */
    public function addRequiredPackage(Package $requiredPackage)
    {
        $this->requiredPackageList[] = $requiredPackage;
    }

    /**
     * @param Package $requiredDevPackage
     */
    public function addRequiredDevPackage(Package $requiredDevPackage)
    {
        $this->requiredDevPackageList[] = $requiredDevPackage;
    }

    /**
     * @param Script $script
     */
    public function addScript(Script $script)
    {
        $this->scriptList[] = $script;
    }

    /**
     * @return string
     */
    public function getPackageName()
    {
        return $this->packageName;
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
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @return string
     */
    public function getPackageVersion()
    {
        return $this->packageVersion;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string[]
     */
    public function getKeywordList()
    {
        return $this->keywordList;
    }

    /**
     * @return Author[]
     */
    public function getAuthorList()
    {
        return $this->authorList;
    }

    /**
     * @return Package[]
     */
    public function getProvidedPackageList()
    {
        return $this->providedPackageList;
    }

    /**
     * @return SuggestedPackage[]
     */
    public function getSuggestedPackageList()
    {
        return $this->suggestedPackageList;
    }

    /**
     * @return Support[]
     */
    public function getSupportList()
    {
        return $this->supportList;
    }

    /**
     * @return Autoload[]
     */
    public function getAutoloadList()
    {
        return $this->autoloadList;
    }

    /**
     * @return Autoload[]
     */
    public function getAutoloadDevList()
    {
        return $this->autoloadDevList;
    }

    /**
     * @return Package[]
     */
    public function getRequiredPackageList()
    {
        return $this->requiredPackageList;
    }

    /**
     * @return Package[]
     */
    public function getRequiredDevPackageList()
    {
        return $this->requiredDevPackageList;
    }

    /**
     * @return Script[]
     */
    public function getScriptList()
    {
        return $this->scriptList;
    }
}