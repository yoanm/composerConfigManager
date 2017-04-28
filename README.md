# composerConfigManager
[![Scrutinizer Build Status](https://img.shields.io/scrutinizer/build/g/yoanm/composerConfigManager.svg?label=Scrutinizer)](https://scrutinizer-ci.com/g/yoanm/composerConfigManager/?branch=master) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/yoanm/composerConfigManager.svg?label=Code%20quality)](https://scrutinizer-ci.com/g/yoanm/composerConfigManager/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/yoanm/composerConfigManager.svg?label=Coverage)](https://scrutinizer-ci.com/g/yoanm/composerConfigManager/?branch=master)

[![Latest Stable Version](https://img.shields.io/packagist/v/yoanm/composer-config-manager.svg)](https://packagist.org/packages/yoanm/composer-config-manager)

Command to manage composer configuration file

  * [Install](#install)
  * [How to](#how-to)
  * [In the box](#in-the-box)
    * [Command line arguments](#in-the-box-command-line-arguments)
    * [Command line options](#in-the-box-command-line-options)
  * [Full composer configuration](#full-composer-configuration)
  * [Contributing](#contributing)

## Install
```bash
composer require --global yoanm/composer-config-manager
```

## How to

just type the following
```bash
BIN_PATH/console "vendor/package-name" path/to/repository/directory
```

In case you launch the command from the repository directory, you can simply use 
```bash
BIN_PATH/console "vendor/package-name"
```

See below for more information regarding command line options

## In the box

<a name="in-the-box-command-line-arguments"></a>
### Command line arguments

  * `"vendor/package-name"` The package name
  * `"path/to/repository/directory"` Path where composer.json file will be created. *(default current directory)*

<a name="in-the-box-command-line-options"></a>
### Command line options

  * `--type package-type` *Default value is "library"*
  * `--license "LICENSE_TYPE"`
  * `--version "X.Y.Z"`
  * `--description "package description"`
  * `--keywords "KEYWORD1"` *Many allowed*
  * `--author"name1#email#role"` *Many allowed*  
  * `--provided-package "package-1#~X.Y"` *Many allowed*
  * `--suggested-package "package-1#description1"` *Many allowed*
  * `--support-type "type1#url1"` *Many allowed*
  * Autoload *Many allowed*
    
    * `--autoload-psr0 "RootNamespace\SubNamespace#path"`
    
      Will append `"\\RootNamespace\\SubNamespace": "path"` under `autoload` -> `psr-0` 
    * `--autoload-psr-4 "RootNamespace\SubNamespace#path"` 
    
      Will append `"\\RootNamespace\\SubNamespace\\": "path"` under `autoload` -> `psr-4` 

  * Autoload dev *Many allowed*
    
    * `--autoload-dev-psr0 "RootNamespace\SubNamespace#path"`
    
      Will append `"\\RootNamespace\\SubNamespace": "path"` under `autoload-dev` -> `psr-0` 
    * `--autoload-dev-psr-4 "RootNamespace\SubNamespace#path"` 
    
      Will append `"\\RootNamespace\\SubNamespace\\": "path"` under `autoload-dev` -> `psr-4` 

  * `--require "vendor/package-name#~x.y"` *Many allowed*
    
      Will append `"vendor/package-name": "~x.y"` under `require`

  * `--require-dev "vendor/package-name#~x.y"` *Many allowed*
    
      Will append `"vendor/package-name": "~x.y"` under `require-dev`
  
  * `--script "script-name#command"` *Many allowed*
  
      Will append `"command"` under `script` -> `script-name` 

```json
"scripts": {
  "script-name": [
    "command"
  ]
}
```

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

## Contributing
See [contributing note](./CONTRIBUTING.md)
