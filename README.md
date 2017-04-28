# composerConfigManager
[![Scrutinizer Build Status](https://img.shields.io/scrutinizer/build/g/yoanm/composerConfigManager.svg?label=Scrutinizer)](https://scrutinizer-ci.com/g/yoanm/composerConfigManager/?branch=master) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/yoanm/composerConfigManager.svg?label=Code%20quality)](https://scrutinizer-ci.com/g/yoanm/composerConfigManager/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/yoanm/composerConfigManager.svg?label=Coverage)](https://scrutinizer-ci.com/g/yoanm/composerConfigManager/?branch=master)

[![Latest Stable Version](https://img.shields.io/packagist/v/yoanm/composer-config-manager.svg)](https://packagist.org/packages/yoanm/composer-config-manager)

Command to manage composer configuration file

  * [Install](#install)
  * [How to](#how-to)
  * [Managed properties](#managed-properties)
  * [Full composer configuration](#full-composer-configuration)
  * [Contributing](#contributing)

<a name="install"></a>
## Install
```bash
composer global require yoanm/composer-config-manager
```
 Add the following in your `.bashrc` file : 
```bash
export PATH=~/.composer/vendor/bin:$PATH 
```

<a name="how-to"></a>
## How to

just type the following
```bash
composercm create "vendor/package-name" path/to/repository/directory [OPTIONS]
composercm update path/to/repository/directory [OPTIONS]
```

In case you launch the command from the repository directory, you can simply use 
```bash
composercm create "vendor/package-name" [OPTIONS]
composercm update [OPTIONS]
```

  * Type `composercm list` to list all available command
  * Type `composercm help COMMAND_NAME` or `composercm COMMAND_NAME -h` to display help for a specific command

See below for more information regarding command line options

<a name="managed-properties"></a>
## Managed properties

  * Package name
  * Package type *Default value is "library"*
  * License *Default value is "MIT"*
  * Version
  * Description
  * Keywords *Many allowed*
  * Author *Many allowed*  
  * Provided *Many allowed*
  * Suggested *Many allowed*
  * Support *Many allowed*
  * PSR-0 / PSR-4 Autoload *Many allowed*
  * PSR-0 / PSR-4 Autoload dev *Many allowed*
  * Required packages *Many allowed*
  * Required dev packages *Many allowed*
  * Scripts

<a name="full-composer-configuration"></a>
## Full composer configuration

```json
{
  "name": "vendor/package-name",
  "type": "library",
  "license": "LICENSE_TYPE",
  "version": "X.Y.Z",
  "description": "package description",
  "keywords": ["KEYWORD1", "KEYWORD2"],
  "authors": [
    {
      "name": "name1",
      "email": "email1",
      "role": "role1"
    },
    {
      "name": "name2",
      "email": "email2",
      "role": "role2"
    }
  ],
  "provide": {
    "package1": "~x.y",
    "package2": "x.y.z",
  },
  "suggest": {
    "package1": "Description 1",
    "package2": "Description 2",
  },
  "support": {
    "type1": "url1"
  },
  "autoload": {
    "psr-0": {
      "\\RootNamespace\\SubNamespace": "path"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "\\RootNamespace\\SubNamespace\\": "path"
    }
  },
  "require": {
    "requirement1": ">=x.y"
  },
  "require-dev": {
    "requirement1": ">=x.y"
  },
  "scripts": {
    "script-1": [
      "command_1",
      "command_2"
    ],
    "script-2": [
      "command_1",
      "command_2"
    ],
  }
}

```

<a name="contributing"></a>
## Contributing
See [contributing note](./CONTRIBUTING.md)
