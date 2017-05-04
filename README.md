# composerConfigManager
[![Scrutinizer Build Status](https://img.shields.io/scrutinizer/build/g/yoanm/composerConfigManager.svg?label=Scrutinizer)](https://scrutinizer-ci.com/g/yoanm/composerConfigManager/?branch=master) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/yoanm/composerConfigManager.svg?label=Code%20quality)](https://scrutinizer-ci.com/g/yoanm/composerConfigManager/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/yoanm/composerConfigManager.svg?label=Coverage)](https://scrutinizer-ci.com/g/yoanm/composerConfigManager/?branch=master)

[![Travis Build Status](https://img.shields.io/travis/yoanm/composerConfigManager/master.svg?label=travis)](https://travis-ci.org/yoanm/composerConfigManager) [![PHP Versions](https://img.shields.io/badge/php-5.5%20%2F%205.6%20%2F%207.0-8892BF.svg)](https://php.net/)

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

 * `composercm update` command will take the current composer.json file and will applied given values
 
 ### Create
 * A `--template` option is available, given values will be applied to the template
 * Values are appended in a default order
   
### Update
 * A `--template` option is available, see below how template are managed
 * Key order are kept from old configuration file. New one are appended in a default order
 
### Templates

 * Multiple template could be provided. Update workflow is the following
   * 1 - Templates between them
     If more than one template is given, 
     * the first first one is updated with values from the second one
     * resulting configuration is updated with third template

     ...

     * resulting configuration is updated with X template
   * 2 - Resulting configuration with existing one 
     * **For update command only, in case at least a template was given**
   * 3 - Resulting configuration with command line values
     * **Could by skipped if only templates are used**

### Key order

 * By default key order as the one defined in composer documentation website
 * It's possible to use the `--template` option to define key order of final configuration
   
   For instance, defined a template file name `key_order.json` with following content : 
   
 ```json
 {
   "name": null,
   "type": null,
   "license": null,
   "version": null,
   "description": null,
   "keywords": [],
   "authors": {},
   "provide": {},
   "suggest": {},
   "support": {},
   "autoload": {},
   "autoload-dev": {},
   "require": {},
   "require-dev": {},
   "scripts": {}
 }
 ```

   Then use the following command : 

 ```bash
 composercm [create|update] [ARGS] [OPTIONS] --template key_order.json
 ```

   Resulting file will have keys ordered like in `key_order.json` file. All keys could be added in `key_order.json`, in case no value is given for a key, key will not appear in final file.
   
   In case you also want to provide a template with default value, use the following:
   
 ```bash
 composercm [create|update] [ARGS] [OPTIONS] --template key_order.json --template default_values.json [--template another.json]
 ```

<a name="managed-properties"></a>
## Managed properties

Following properties could be defined with option from command line : 

  * Package name
  * Package type
  * License
  * Version
  * Description
  * Keywords *Many allowed*
  * Author *Many allowed*  
  * Provided packages *Many allowed*
  * Suggested packages *Many allowed*
  * Support *Many allowed*
  * PSR-0 / PSR-4 Autoload *Many allowed*
  * PSR-0 / PSR-4 Autoload dev *Many allowed*
  * Required packages *Many allowed*
  * Required dev packages *Many allowed*
  * Scripts
  
All others properties could ever be defined in templates and will be managed in a default way (could produce unexpected merge for complex nested values)

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
