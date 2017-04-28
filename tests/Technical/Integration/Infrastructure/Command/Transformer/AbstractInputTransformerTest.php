<?php
namespace Technical\Integration\Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer;

use Yoanm\ComposerConfigManager\Domain\Model\Author;
use Yoanm\ComposerConfigManager\Domain\Model\Autoload;
use Yoanm\ComposerConfigManager\Domain\Model\AutoloadEntry;
use Yoanm\ComposerConfigManager\Domain\Model\Package;
use Yoanm\ComposerConfigManager\Domain\Model\Script;
use Yoanm\ComposerConfigManager\Domain\Model\SuggestedPackage;
use Yoanm\ComposerConfigManager\Domain\Model\Support;
use Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer\InputTransformer;

abstract class AbstractInputTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var InputTransformer */
    protected $transformer;

    public function setUp()
    {
        $this->transformer = new InputTransformer();
    }

    /**
     * @param Author[] $list
     * @param array    $expected
     */
    protected function assertAuthorList(array $list, array $expected)
    {
        $this->assertTrue(is_array($list), 'AuthorList must be an array');
        $this->assertCount(count($expected), $list, 'Unexpected Author count');
        $this->assertContainsOnlyInstancesOf(Author::class, $list, 'AuthorList contains invalid instance');
        foreach ($list as $key => $anAuthor) {
            $this->assertSame($expected[$key][0], $anAuthor->getName());
            $this->assertSame($expected[$key][1], $anAuthor->getEmail());
            $this->assertSame($expected[$key][2], $anAuthor->getRole());
        }
    }

    /**
     * @param Package[] $list
     * @param array     $expectedList
     */
    protected function assertPackageList(array $list, array $expectedList)
    {
        $this->assertTrue(is_array($list), 'PackageList must be an array');
        $this->assertCount(count($expectedList), $list, 'Unexpected Package count');
        $this->assertContainsOnlyInstancesOf(Package::class, $list, 'PackageList contains invalid instance');
        foreach ($list as $key => $package) {
            $this->assertSame($expectedList[$key][0], $package->getName());
            $this->assertSame($expectedList[$key][1], $package->getVersionConstraint());
        }
    }

    /**
     * @param SuggestedPackage[] $list
     * @param array              $expectedList
     */
    protected function assertSuggestedPackageList(array $list, array $expectedList)
    {
        $this->assertTrue(is_array($list), 'SuggestedPackageList must be an array');
        $this->assertCount(count($expectedList), $list, 'Unexpected SuggestedPackage count');
        $this->assertContainsOnlyInstancesOf(
            SuggestedPackage::class,
            $list,
            'SuggestedPackageList contains invalid instance'
        );
        foreach ($list as $key => $package) {
            $this->assertSame($expectedList[$key][0], $package->getName());
            $this->assertSame($expectedList[$key][1], $package->getDescription());
        }
    }

    /**
     * @param Support[] $list
     * @param array     $expectedList
     */
    protected function assertSupportList(array $list, array $expectedList)
    {
        $this->assertTrue(is_array($list), 'SupportList must be an array');
        $this->assertCount(count($expectedList), $list, 'Unexpected Support count');
        $this->assertContainsOnlyInstancesOf(Support::class, $list, 'SupportList contains invalid instance');
        foreach ($list as $key => $support) {
            $this->assertSame($expectedList[$key][0], $support->getType());
            $this->assertSame($expectedList[$key][1], $support->getUrl());
        }
    }

    /**
     * @param Autoload[] $autoloadList
     * @param array      $expectedList
     */
    protected function assertAutoloadList(array $autoloadList, array $expectedList)
    {
        $this->assertTrue(is_array($autoloadList), 'AutoloadList must be an array');
        $this->assertCount(count($expectedList), $autoloadList, 'Unexpected Autoload count');
        $this->assertContainsOnlyInstancesOf(Autoload::class, $autoloadList, 'AutoloadList contains invalid instance');

        $counter = 0;
        foreach ($autoloadList as $key => $curent) {
            if (isset($expectedList[$counter]) && is_array($expectedList[$counter])) {
                $expected = $expectedList[$counter];
                $this->assertSame($expected[0], $curent->getType());
                $this->assertSame($expected[1], $curent->getNamespace());
                $this->assertSame($expected[2], $curent->getPath());
            }
            $counter++;
        }
    }

    /**
     * @param Script[] $scriptList
     * @param array    $expectedList
     */
    protected function assertScriptList(array $scriptList, array $expectedList)
    {
        $this->assertTrue(is_array($scriptList), 'ScriptList must be an array');
        $this->assertCount(count($expectedList), $scriptList, 'Unexpected Script count');
        $this->assertContainsOnlyInstancesOf(Script::class, $scriptList, 'ScriptList contains invalid instance');
        foreach ($scriptList as $key => $script) {
            $this->assertSame($expectedList[$key][0], $script->getName());
            $this->assertSame($expectedList[$key][1], $script->getCommand());
        }
    }
}
