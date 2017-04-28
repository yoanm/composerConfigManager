Feature: As user
  In order to create composer configuration file
  I should be able to use the "update" command line

  Scenario: Specify location
    Given I have the folder "./build/test"
    When I execute composercm update with "./build/test" and following options:
    """
    --package-name "pk_namespace2\\pk_name2"
    """
    Then I should have a configuration file at "./build/test"
    Then configuration file at "./build/test" should contains:
    """
    {
      "name": "pk_namespace2\\pk_name2",
      "type": "default-type",
      "license": "default-license",
      "version": "default-version",
      "description": "default-description"
    }
    """

  Scenario: Basic configuration file
    Given I execute composercm update with following options:
    """
    --package-name "pk_namespace2\\pk_name2" --description "pk description" --type my_type --license my_license --package-version 1.2.3
    """
    Then configuration file should contains:
    """
    {
      "name": "pk_namespace2\\pk_name2",
      "type": "my_type",
      "license": "my_license",
      "version": "1.2.3",
      "description": "pk description"
    }
    """

  Scenario: Multiple keywords
    Given I execute composercm update with following options:
    """
    --keyword my_keyword --keyword my_keyword2
    """
    Then configuration file should contains:
    """
    {
      "keywords": ["DEFAULT-KEYWORD1", "DEFAULT-KEYWORD2", "my_keyword", "my_keyword2"]
    }
    """

  Scenario: Multiple authors
    Given I execute composercm update with following options:
    """
    --author author#email#role --author name2 --author name3#email3
    """
    Then configuration file should contains:
    """
    {
      "authors": [
        {
          "name": "default-name1",
          "email": "default-email1",
          "role": "default-role1"
        },
        {
          "name": "default-name2",
          "email": "default-email2",
          "role": "default-role2"
        },
        {
          "name": "author",
          "email": "email",
          "role": "role"
        },
        {
          "name": "name2"
        },
        {
          "name": "name3",
          "email": "email3"
        }
      ]
    }
    """

  Scenario: Update some authors
    Given I execute composercm update with following options:
    """
    --author default-name1#email#role --author default-name2#email2 --author name3#email3
    """
    Then configuration file should contains:
    """
    {
      "authors": [
        {
          "name": "default-name1",
          "email": "email",
          "role": "role"
        },
        {
          "name": "default-name2",
          "email": "email2",
          "role": "default-role2"
        },
        {
          "name": "name3",
          "email": "email3"
        }
      ]
    }
    """

  Scenario: Multiple provided packages
    Given I execute composercm update with following options:
    """
    --provided-package name/A#url1 --provided-package name2/B#url2 --provided-package name/C#url3
    """
    Then configuration file should contains:
    """
    {
      "provide": {
        "package1": "default-provided-package1",
        "package2": "default-provided-package2",
        "name/A": "url1",
        "name2/B": "url2",
        "name/C": "url3"
      }
    }
    """

  Scenario: Update some provided packages
    Given I execute composercm update with following options:
    """
    --provided-package package1#url1 --provided-package name2/B#url2
    """
    Then configuration file should contains:
    """
    {
      "provide": {
        "package1": "url1",
        "package2": "default-provided-package2",
        "name2/B": "url2"
      }
    }
    """

  Scenario: Multiple some suggested packages
    Given I execute composercm update with following options:
    """
    --suggested-package "name/A#description 1" --suggested-package "name2/B#description 2" --suggested-package "name/C#description 3"
    """
    Then configuration file should contains:
    """
    {
      "suggest": {
        "package1": "default-suggested-package1",
        "package2": "default-suggested-package2",
        "name/A": "description 1",
        "name2/B": "description 2",
        "name/C": "description 3"
      }
    }
    """

  Scenario: Update some suggested packages
    Given I execute composercm update with following options:
    """
    --suggested-package "package1#description 1" --suggested-package "name2/B#description 2"
    """
    Then configuration file should contains:
    """
    {
      "suggest": {
        "package1": "description 1",
        "package2": "default-suggested-package2",
        "name2/B": "description 2"
      }
    }
    """

  Scenario: Multiple supports
    Given I execute composercm update with following options:
    """
    --support "typeA#urlA" --support "typeB#urlB" --support "typeC#urlC"
    """
    Then configuration file should contains:
    """
    {
      "support": {
        "type1": "default-support-type1",
        "type2": "default-support-type2",
        "typeA": "urlA",
        "typeB": "urlB",
        "typeC": "urlC"
      }
    }
    """

  Scenario: Update smoe supports
    Given I execute composercm update with following options:
    """
    --support "type1#url1" --support "typeA#urlA"
    """
    Then configuration file should contains:
    """
    {
      "support": {
        "type1": "url1",
        "type2": "default-support-type2",
        "typeA": "urlA"
      }
    }
    """

    @yo
  Scenario: Multiple autoload (mixing PSR-0 and PSR-4)
    Given I execute composercm update with following options:
    """
    --autoload-psr0 "vendor1\\Test#src1" --autoload-psr4 "vendor2\\Test#src2" --autoload-psr0 "vendor1\\Test2#src3"
    """
    Then configuration file should contains:
    """
    {
      "autoload": {
        "psr-0": {
         "DefaultNamespace\\DefaultSubNamespace": "default-psr0-path1",
         "DefaultNamespace\\DefaultSubNamespace2": "default-psr0-path2",
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\DefaultNamespace\\DefaultSubNamespace\\": "default-psr4-path1",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "default-psr4-path2",
          "vendor2\\Test": "src2"
        }
      }
    }
    """

  Scenario: Update some autoload (mixing PSR-0 and PSR-4)
    Given I execute composercm update with following options:
    """
    --autoload-psr0 "DefaultNamespace\\DefaultSubNamespace#src1" --autoload-psr4 "\\DefaultNamespace\\DefaultSubNamespace2\\#src2" --autoload-psr4 "vendor2\\Test#src4" --autoload-psr0 "vendor1\\Test2#src3"
    """
    Then configuration file should contains:
    """
    {
      "autoload": {
        "psr-0": {
         "DefaultNamespace\\DefaultSubNamespace": "src1",
         "DefaultNamespace\\DefaultSubNamespace2": "default-psr0-path2",
         "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\DefaultNamespace\\DefaultSubNamespace\\": "default-psr4-path1",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "src2",
          "vendor2\\Test": "src4"
        }
      }
    }
    """

  Scenario: Multiple autoload dev (mixing PSR-0 and PSR-4)
    Given I execute composercm update with following options:
    """
    --autoload-dev-psr0 "vendor1\\Test#src1" --autoload-dev-psr4 "vendor2\\Test#src2" --autoload-dev-psr0 "vendor1\\Test2#src3"
    """
    Then configuration file should contains:
    """
    {
      "autoload-dev": {
        "psr-0": {
          "DefaultNamespace\\DefaultSubNamespace": "default-psr0-path1",
          "DefaultNamespace\\DefaultSubNamespace2": "default-psr0-path2",
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\DefaultNamespace\\DefaultSubNamespace\\": "default-psr4-path1",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "default-psr4-path2",
          "vendor2\\Test": "src2"
        }
      }
    }
    """

  Scenario: Update some autoload dev (mixing PSR-0 and PSR-4)
    Given I execute composercm update with following options:
    """
    --autoload-dev-psr0 "DefaultNamespace\\DefaultSubNamespace2#src1" --autoload-dev-psr4 "\\DefaultNamespace\\DefaultSubNamespace\\#src2" --autoload-dev-psr4 "vendor2\\Test#src2" --autoload-dev-psr0 "vendor1\\Test2#src3"
    """
    Then configuration file should contains:
    """
    {
      "autoload-dev": {
        "psr-0": {
          "DefaultNamespace\\DefaultSubNamespace": "default-psr0-path1",
          "DefaultNamespace\\DefaultSubNamespace2": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\DefaultNamespace\\DefaultSubNamespace\\": "src2",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "default-psr4-path2",
          "vendor2\\Test": "src2"
        }
      }
    }
    """

  Scenario: Multiple require
    Given I execute composercm update with following options:
    """
    --require "vendor1/A#v1.3.0" --require "vendor2/B#>=2.0.0" --require "vendor1/C#~3.2"
    """
    Then configuration file should contains:
    """
    {
      "require": {
        "requirement1": "default-required-package1",
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """

  Scenario: Update some require
    Given I execute composercm update with following options:
    """
    --require "requirement1#custom" --require "vendor2/B#>=2.0.0"
    """
    Then configuration file should contains:
    """
    {
      "require": {
        "requirement1": "custom",
        "vendor2/B": ">=2.0.0"
      }
    }
    """

  Scenario: Multiple require dev
    Given I execute composercm update with following options:
    """
    --require-dev "vendor1/A#v1.3.0" --require-dev "vendor2/B#>=2.0.0" --require-dev "vendor1/C#~3.2"
    """
    Then configuration file should contains:
    """
    {
      "require-dev": {
        "requirement1": "default-required-dev-package1",
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """

  Scenario: Update some require dev
    Given I execute composercm update with following options:
    """
    --require-dev "requirement1#custom" --require-dev "vendor2/B#>=2.0.0"
    """
    Then configuration file should contains:
    """
    {
      "require-dev": {
        "requirement1": "custom",
        "vendor2/B": ">=2.0.0"
      }
    }
    """

  Scenario: Multiple scripts
    Given I execute composercm update with following options:
    """
    --script "name1#command1" --script "name2#command1" --script "name1#command2"
    """
    Then configuration file should contains:
    """
    {
      "scripts": {
        "default-script-1": [
            "default-script1-command_1",
            "default-script1-command_2"
        ],
        "default-script-2": [
            "default-script2-command_1",
            "default-script2-command_2"
        ],
        "name1": [
          "command1",
          "command2"
        ],
        "name2": [
          "command1"
        ]
      }
    }
    """

  Scenario: Update some scripts
    Given I execute composercm update with following options:
    """
    --script "default-script-1#command1" --script "name2#command1" --script "default-script-1#command2"
    """
    Then configuration file should contains:
    """
    {
      "scripts": {
        "default-script-1": [
            "command1",
            "command2"
        ],
        "default-script-2": [
            "default-script2-command_1",
            "default-script2-command_2"
        ],
        "name2": [
          "command1"
        ]
      }
    }
    """