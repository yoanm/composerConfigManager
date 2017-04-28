<?php
namespace Technical\Integration\Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer;

use Yoanm\ComposerConfigManager\Application\WriteConfigurationRequest;
use Yoanm\ComposerConfigManager\Domain\Model\Autoload;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer\InputTransformer;

class InputTransformerMultipleTest extends AbstractInputTransformerTest
{
    public function testMultipleProvidedPackages()
    {
        $package1Name = 'package1-name';
        $package1Version = 'package1-version';
        $package2Name = 'package2-name';
        $package2Version = 'package2-version';
        $package3Name = 'package3-name';
        $package3Version = 'package3-version';

        $argumentList = [
            InputTransformer::ARGUMENT_PACKAGE_NAME => 'package-name',
            InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER => 'destination',
        ];

        $optionList = [
            InputTransformer::OPTION_PROVIDED_PACKAGE => [
                $package1Name.InputTransformer::SEPARATOR.$package1Version,
                $package2Name.InputTransformer::SEPARATOR.$package2Version,
                $package3Name.InputTransformer::SEPARATOR.$package3Version,
            ],
        ];

        $request = $this->transformer->fromCommandLine($argumentList, $optionList);

        $this->assertInstanceOf(WriteConfigurationRequest::class, $request);
        $configuration = $request->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertPackageList(
            $configuration->getProvidedPackageList(),
            [
                [$package1Name, $package1Version],
                [$package2Name, $package2Version],
                [$package3Name, $package3Version],
            ]
        );
    }

    public function testMultipleSuggestedPackages()
    {
        $package1Name = 'package1-name';
        $package1Description = 'package1-description';
        $package2Name = 'package2-name';
        $package2Description = 'package2-description';
        $package3Name = 'package3-name';
        $package3Description = 'package3-description';

        $argumentList = [
            InputTransformer::ARGUMENT_PACKAGE_NAME => 'package-name',
            InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER => 'destination',
        ];

        $optionList = [
            InputTransformer::OPTION_SUGGESTED_PACKAGE=> [
                $package1Name.InputTransformer::SEPARATOR.$package1Description,
                $package2Name.InputTransformer::SEPARATOR.$package2Description,
                $package3Name.InputTransformer::SEPARATOR.$package3Description,
            ],
        ];

        $request = $this->transformer->fromCommandLine($argumentList, $optionList);

        $this->assertInstanceOf(WriteConfigurationRequest::class, $request);
        $configuration = $request->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertSuggestedPackageList(
            $configuration->getSuggestedPackageList(),
            [
                [$package1Name, $package1Description],
                [$package2Name, $package2Description],
                [$package3Name, $package3Description],
            ]
        );
    }

    public function testMultipleSupport()
    {
        $support1Type = 'support1-name';
        $support1Url = 'support1-url';
        $support2Type = 'support2-name';
        $support2Url = 'support2-url';
        $support3Type = 'support3-name';
        $support3Url = 'support3-url';

        $argumentList = [
            InputTransformer::ARGUMENT_PACKAGE_NAME => 'package-name',
            InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER => 'destination',
        ];

        $optionList = [
            InputTransformer::OPTION_SUPPORT=> [
                $support1Type.InputTransformer::SEPARATOR.$support1Url,
                $support2Type.InputTransformer::SEPARATOR.$support2Url,
                $support3Type.InputTransformer::SEPARATOR.$support3Url,
            ],
        ];

        $request = $this->transformer->fromCommandLine($argumentList, $optionList);

        $this->assertInstanceOf(WriteConfigurationRequest::class, $request);
        $configuration = $request->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertSupportList(
            $configuration->getSupportList(),
            [
                [$support1Type, $support1Url],
                [$support2Type, $support2Url],
                [$support3Type, $support3Url],
            ]
        );
    }

    public function testMultipleAutoload()
    {
        $autoload1Psr0Namespace = 'autoload1-psr0-namespace';
        $autoload1Psr0Path = 'autoload1-psr0-path';
        $autoload1Psr4Namespace = 'autoload1-psr4-namespace';
        $autoload1Psr4Path = 'autoload1-psr4-path';
        $autoload2Psr0Namespace = 'autoload2-psr0-namespace';
        $autoload2Psr0Path = 'autoload2-psr0-path';
        $autoload2Psr4Namespace = 'autoload2-psr4-namespace';
        $autoload2Psr4Path = 'autoload2-psr4-path';
        $autoload3Psr0Namespace = 'autoload3-psr0-namespace';
        $autoload3Psr0Path = 'autoload3-psr0-path';
        $autoload3Psr4Namespace = 'autoload3-psr4-namespace';
        $autoload3Psr4Path = 'autoload3-psr4-path';


        $argumentList = [
            InputTransformer::ARGUMENT_PACKAGE_NAME => 'package-name',
            InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER => 'destination',
        ];

        $optionList = [
            InputTransformer::OPTION_AUTOLOAD_PSR0 => [
                $autoload1Psr0Namespace.InputTransformer::SEPARATOR.$autoload1Psr0Path,
                $autoload2Psr0Namespace.InputTransformer::SEPARATOR.$autoload2Psr0Path,
                $autoload3Psr0Namespace.InputTransformer::SEPARATOR.$autoload3Psr0Path,
            ],
            InputTransformer::OPTION_AUTOLOAD_PSR4 => [
                $autoload1Psr4Namespace.InputTransformer::SEPARATOR.$autoload1Psr4Path,
                $autoload2Psr4Namespace.InputTransformer::SEPARATOR.$autoload2Psr4Path,
                $autoload3Psr4Namespace.InputTransformer::SEPARATOR.$autoload3Psr4Path,
            ],
        ];

        $request = $this->transformer->fromCommandLine($argumentList, $optionList);

        $this->assertInstanceOf(WriteConfigurationRequest::class, $request);
        $configuration = $request->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);

        $this->assertAutoloadList(
            $configuration->getAutoloadList(),
            [
                Autoload::TYPE_PSR0 => [
                    [$autoload1Psr0Namespace, $autoload1Psr0Path],
                    [$autoload2Psr0Namespace, $autoload2Psr0Path],
                    [$autoload3Psr0Namespace, $autoload3Psr0Path],
                ],
                Autoload::TYPE_PSR4 => [
                    [$autoload1Psr4Namespace, $autoload1Psr4Path],
                    [$autoload2Psr4Namespace, $autoload2Psr4Path],
                    [$autoload3Psr4Namespace, $autoload3Psr4Path],
                ],
            ]
        );
    }

    public function testMultipleAutoloadDev()
    {
        $autoload1Psr0Namespace = 'autoload1-psr0-namespace';
        $autoload1Psr0Path = 'autoload1-psr0-path';
        $autoload1Psr4Namespace = 'autoload1-psr4-namespace';
        $autoload1Psr4Path = 'autoload1-psr4-path';
        $autoload2Psr0Namespace = 'autoload2-psr0-namespace';
        $autoload2Psr0Path = 'autoload2-psr0-path';
        $autoload2Psr4Namespace = 'autoload2-psr4-namespace';
        $autoload2Psr4Path = 'autoload2-psr4-path';
        $autoload3Psr0Namespace = 'autoload3-psr0-namespace';
        $autoload3Psr0Path = 'autoload3-psr0-path';
        $autoload3Psr4Namespace = 'autoload3-psr4-namespace';
        $autoload3Psr4Path = 'autoload3-psr4-path';


        $argumentList = [
            InputTransformer::ARGUMENT_PACKAGE_NAME => 'package-name',
            InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER => 'destination',
        ];

        $optionList = [
            InputTransformer::OPTION_AUTOLOAD_DEV_PSR0 => [
                $autoload1Psr0Namespace.InputTransformer::SEPARATOR.$autoload1Psr0Path,
                $autoload2Psr0Namespace.InputTransformer::SEPARATOR.$autoload2Psr0Path,
                $autoload3Psr0Namespace.InputTransformer::SEPARATOR.$autoload3Psr0Path,
            ],
            InputTransformer::OPTION_AUTOLOAD_DEV_PSR4 => [
                $autoload1Psr4Namespace.InputTransformer::SEPARATOR.$autoload1Psr4Path,
                $autoload2Psr4Namespace.InputTransformer::SEPARATOR.$autoload2Psr4Path,
                $autoload3Psr4Namespace.InputTransformer::SEPARATOR.$autoload3Psr4Path,
            ],
        ];

        $request = $this->transformer->fromCommandLine($argumentList, $optionList);

        $this->assertInstanceOf(WriteConfigurationRequest::class, $request);
        $configuration = $request->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);

        $this->assertAutoloadList(
            $configuration->getAutoloadDevList(),
            [
                Autoload::TYPE_PSR0 => [
                    [$autoload1Psr0Namespace, $autoload1Psr0Path],
                    [$autoload2Psr0Namespace, $autoload2Psr0Path],
                    [$autoload3Psr0Namespace, $autoload3Psr0Path],
                ],
                Autoload::TYPE_PSR4 => [
                    [$autoload1Psr4Namespace, $autoload1Psr4Path],
                    [$autoload2Psr4Namespace, $autoload2Psr4Path],
                    [$autoload3Psr4Namespace, $autoload3Psr4Path],
                ],
            ]
        );
    }

    public function testMultipleRequiredPackage()
    {
        $package1Name = 'package1-name';
        $package1Version = 'package1-version';
        $package2Name = 'package2-name';
        $package2Version = 'package2-version';
        $package3Name = 'package3-name';
        $package3Version = 'package3-version';

        $argumentList = [
            InputTransformer::ARGUMENT_PACKAGE_NAME => 'package-name',
            InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER => 'destination',
        ];

        $optionList = [
            InputTransformer::OPTION_REQUIRE=> [
                $package1Name.InputTransformer::SEPARATOR.$package1Version,
                $package2Name.InputTransformer::SEPARATOR.$package2Version,
                $package3Name.InputTransformer::SEPARATOR.$package3Version,
            ],
        ];

        $request = $this->transformer->fromCommandLine($argumentList, $optionList);

        $this->assertInstanceOf(WriteConfigurationRequest::class, $request);
        $configuration = $request->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertPackageList(
            $configuration->getRequiredPackageList(),
            [
                [$package1Name, $package1Version],
                [$package2Name, $package2Version],
                [$package3Name, $package3Version],
            ]
        );
    }

    public function testMultipleRequiredDevPackage()
    {
        $package1Name = 'package1-name';
        $package1Version = 'package1-version';
        $package2Name = 'package2-name';
        $package2Version = 'package2-version';
        $package3Name = 'package3-name';
        $package3Version = 'package3-version';

        $argumentList = [
            InputTransformer::ARGUMENT_PACKAGE_NAME => 'package-name',
            InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER => 'destination',
        ];

        $optionList = [
            InputTransformer::OPTION_REQUIRE_DEV=> [
                $package1Name.InputTransformer::SEPARATOR.$package1Version,
                $package2Name.InputTransformer::SEPARATOR.$package2Version,
                $package3Name.InputTransformer::SEPARATOR.$package3Version,
            ],
        ];

        $request = $this->transformer->fromCommandLine($argumentList, $optionList);

        $this->assertInstanceOf(WriteConfigurationRequest::class, $request);
        $configuration = $request->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertPackageList(
            $configuration->getRequiredDevPackageList(),
            [
                [$package1Name, $package1Version],
                [$package2Name, $package2Version],
                [$package3Name, $package3Version],
            ]
        );
    }

    public function testMultipleScript()
    {
        $script1Name = 'script1-name';
        $script1Command = 'script1-command';
        $script1Command2 = 'script1-command-2';
        $script2Name = 'script2-name';
        $script2Command = 'script2-command';
        $script3Name = 'script3-name';
        $script3Command = 'script1-command-2';

        $argumentList = [
            InputTransformer::ARGUMENT_PACKAGE_NAME => 'package-name',
            InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER => 'destination',
        ];

        $optionList = [
            InputTransformer::OPTION_SCRIPT => [
                $script1Name.InputTransformer::SEPARATOR.$script1Command,
                $script1Name.InputTransformer::SEPARATOR.$script1Command2,
                $script2Name.InputTransformer::SEPARATOR.$script2Command,
                $script3Name.InputTransformer::SEPARATOR.$script3Command,
            ],
        ];

        $request = $this->transformer->fromCommandLine($argumentList, $optionList);

        $this->assertInstanceOf(WriteConfigurationRequest::class, $request);
        $configuration = $request->getConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertScriptList(
            $configuration->getScriptList(),
            [
                [$script1Name, $script1Command],
                [$script1Name, $script1Command2],
                [$script2Name, $script2Command],
                [$script3Name, $script3Command],
            ]
        );
    }
}
