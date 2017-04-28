<?php
namespace Technical\Integration\Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer;

use Yoanm\ComposerConfigManager\Domain\Model\Autoload;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer\InputTransformer;

class InputTransformerTest extends AbstractInputTransformerTest
{
    public function testWholeConfig()
    {
        $keyword = 'keyword';
        $authorName = 'author-name';
        $authorEmail = 'author-email';
        $authorRole = 'author-role';
        $providedPackageName = 'provided-package-name';
        $providedPackageVersion = 'provided-package-version';
        $suggestedPackageName = 'suggested-package-name';
        $suggestedPackageDescription = 'suggested-package-description';
        $supportType = 'support-type';
        $supportUrl = 'support-url';
        $autoloadPsr0Namespace = 'autoload-psr0-namespace';
        $autoloadPsr0Path = 'autoload-psr0-path';
        $autoloadPsr4Namespace = 'autoload-psr4-namespace';
        $autoloadPsr4Path = 'autoload-psr4-path';
        $autoloadDevPsr0Namespace = 'autoload-dev-psr0-namespace';
        $autoloadDevPsr0Path = 'autoload-dev-psr0-path';
        $autoloadDevPsr4Namespace = 'autoload-dev-psr4-namespace';
        $autoloadDevPsr4Path = 'autoload-dev-psr4-path';
        $requireName = 'require-name';
        $requireVersion = 'require-version';
        $requireDevName = 'require-dev-name';
        $requireDevVersion = 'require-dev-version';
        $scriptName = 'script-name';
        $scriptCommand = 'script-command';

        $inputList = [
            InputTransformer::KEY_PACKAGE_NAME => 'package-name',
            InputTransformer::KEY_TYPE => 'type',
            InputTransformer::KEY_LICENSE => 'license',
            InputTransformer::KEY_PACKAGE_VERSION => 'package-version',
            InputTransformer::KEY_DESCRIPTION => 'description',
            InputTransformer::KEY_KEYWORD => [$keyword],
            InputTransformer::KEY_AUTHOR => [
                $authorName.InputTransformer::SEPARATOR.$authorEmail.InputTransformer::SEPARATOR.$authorRole
            ],
            InputTransformer::KEY_PROVIDED_PACKAGE => [
                $providedPackageName.InputTransformer::SEPARATOR.$providedPackageVersion
            ],
            InputTransformer::KEY_SUGGESTED_PACKAGE => [
                $suggestedPackageName.InputTransformer::SEPARATOR.$suggestedPackageDescription
            ],
            InputTransformer::KEY_SUPPORT => [
                $supportType.InputTransformer::SEPARATOR.$supportUrl
            ],
            InputTransformer::KEY_AUTOLOAD_PSR0 => [
                $autoloadPsr0Namespace.InputTransformer::SEPARATOR.$autoloadPsr0Path
            ],
            InputTransformer::KEY_AUTOLOAD_PSR4 => [
                $autoloadPsr4Namespace.InputTransformer::SEPARATOR.$autoloadPsr4Path
            ],
            InputTransformer::KEY_AUTOLOAD_DEV_PSR0 => [
                $autoloadDevPsr0Namespace.InputTransformer::SEPARATOR.$autoloadDevPsr0Path
            ],
            InputTransformer::KEY_AUTOLOAD_DEV_PSR4 => [
                $autoloadDevPsr4Namespace.InputTransformer::SEPARATOR.$autoloadDevPsr4Path
            ],
            InputTransformer::KEY_REQUIRE => [
                $requireName.InputTransformer::SEPARATOR.$requireVersion
            ],
            InputTransformer::KEY_REQUIRE_DEV => [
                $requireDevName.InputTransformer::SEPARATOR.$requireDevVersion
            ],
            InputTransformer::KEY_SCRIPT => [
                $scriptName.InputTransformer::SEPARATOR.$scriptCommand
            ],
        ];

        $configuration = $this->transformer->fromCommandLine($inputList);

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertSame($inputList[InputTransformer::KEY_PACKAGE_NAME], $configuration->getPackageName());
        $this->assertSame($inputList[InputTransformer::KEY_TYPE], $configuration->getType());
        $this->assertSame($inputList[InputTransformer::KEY_LICENSE], $configuration->getLicense());
        $this->assertSame($inputList[InputTransformer::KEY_PACKAGE_VERSION ], $configuration->getPackageVersion());
        $this->assertSame($inputList[InputTransformer::KEY_DESCRIPTION], $configuration->getDescription());
        $this->assertSame([$keyword], $configuration->getKeywordList());

        $this->assertAuthorList($configuration->getAuthorList(), [[$authorName, $authorEmail, $authorRole]]);

        $this->assertPackageList(
            $configuration->getProvidedPackageList(),
            [[$providedPackageName, $providedPackageVersion]]
        );

        $this->assertSuggestedPackageList(
            $configuration->getSuggestedPackageList(),
            [[$suggestedPackageName, $suggestedPackageDescription]]
        );

        $this->assertSupportList(
            $configuration->getSupportList(),
            [[$supportType, $supportUrl]]
        );

        $this->assertAutoloadList(
            $configuration->getAutoloadList(),
            [
                Autoload::TYPE_PSR0 => [[$autoloadPsr0Namespace, $autoloadPsr0Path]],
                Autoload::TYPE_PSR4 => [[$autoloadPsr4Namespace, $autoloadPsr4Path]],
            ]
        );
        $this->assertAutoloadList(
            $configuration->getAutoloadDevList(),
            [
                Autoload::TYPE_PSR0 => [[$autoloadDevPsr0Namespace, $autoloadDevPsr0Path]],
                Autoload::TYPE_PSR4 => [[$autoloadDevPsr4Namespace, $autoloadDevPsr4Path]],
            ]
        );

        $this->assertPackageList(
            $configuration->getRequiredPackageList(),
            [[$requireName, $requireVersion]]
        );
        $this->assertPackageList(
            $configuration->getRequiredDevPackageList(),
            [[$requireDevName, $requireDevVersion]]
        );

        $this->assertScriptList($configuration->getScriptList(), [[$scriptName, $scriptCommand]]);
    }
}
