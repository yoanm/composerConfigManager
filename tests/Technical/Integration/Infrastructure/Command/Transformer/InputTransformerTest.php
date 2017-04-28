<?php
namespace Technical\Integration\Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer;

use Yoanm\ComposerConfigManager\Application\WriteConfigurationRequest;
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

        $argumentList = [
            InputTransformer::ARGUMENT_PACKAGE_NAME => 'package-name',
            InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER => 'destination',
        ];

        $optionList = [
            InputTransformer::OPTION_TYPE => 'type',
            InputTransformer::OPTION_LICENSE => 'license',
            InputTransformer::OPTION_PACKAGE_VERSION => 'package-version',
            InputTransformer::OPTION_DESCRIPTION => 'description',
            InputTransformer::OPTION_KEYWORD => [$keyword],
            InputTransformer::OPTION_AUTHOR => [
                $authorName.InputTransformer::SEPARATOR.$authorEmail.InputTransformer::SEPARATOR.$authorRole
            ],
            InputTransformer::OPTION_PROVIDED_PACKAGE => [
                $providedPackageName.InputTransformer::SEPARATOR.$providedPackageVersion
            ],
            InputTransformer::OPTION_SUGGESTED_PACKAGE => [
                $suggestedPackageName.InputTransformer::SEPARATOR.$suggestedPackageDescription
            ],
            InputTransformer::OPTION_SUPPORT => [
                $supportType.InputTransformer::SEPARATOR.$supportUrl
            ],
            InputTransformer::OPTION_AUTOLOAD_PSR0 => [
                $autoloadPsr0Namespace.InputTransformer::SEPARATOR.$autoloadPsr0Path
            ],
            InputTransformer::OPTION_AUTOLOAD_PSR4 => [
                $autoloadPsr4Namespace.InputTransformer::SEPARATOR.$autoloadPsr4Path
            ],
            InputTransformer::OPTION_AUTOLOAD_DEV_PSR0 => [
                $autoloadDevPsr0Namespace.InputTransformer::SEPARATOR.$autoloadDevPsr0Path
            ],
            InputTransformer::OPTION_AUTOLOAD_DEV_PSR4 => [
                $autoloadDevPsr4Namespace.InputTransformer::SEPARATOR.$autoloadDevPsr4Path
            ],
            InputTransformer::OPTION_REQUIRE => [
                $requireName.InputTransformer::SEPARATOR.$requireVersion
            ],
            InputTransformer::OPTION_REQUIRE_DEV => [
                $requireDevName.InputTransformer::SEPARATOR.$requireDevVersion
            ],
            InputTransformer::OPTION_SCRIPT => [
                $scriptName.InputTransformer::SEPARATOR.$scriptCommand
            ],
        ];

        $request = $this->transformer->fromCommandLine($argumentList, $optionList);

        $this->assertInstanceOf(WriteConfigurationRequest::class, $request);
        $this->assertSame(
            $argumentList[InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER],
            $request->getDestinationFolder()
        );

        $configuration = $request->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertSame($argumentList[InputTransformer::ARGUMENT_PACKAGE_NAME], $configuration->getPackageName());
        $this->assertSame($optionList[InputTransformer::OPTION_TYPE], $configuration->getType());
        $this->assertSame($optionList[InputTransformer::OPTION_LICENSE], $configuration->getLicense());
        $this->assertSame($optionList[InputTransformer::OPTION_PACKAGE_VERSION ], $configuration->getPackageVersion());
        $this->assertSame($optionList[InputTransformer::OPTION_DESCRIPTION], $configuration->getDescription());
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
