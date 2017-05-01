Feature: As user
  In order to create composer configuration file from a template
  I should be able to use the command line

  Scenario: Full configuration
    Given I will use configuration template at "./template/template.json" with:
    """
    {
      "name": "default-template-name",
      "type": "default-template-type",
      "license": "default-template-license",
      "version": "default-template-version",
      "description": "default-template-description",
      "keywords": [
        "DEFAULT-TEMPLATE-KEYWORD1",
        "DEFAULT-TEMPLATE-KEYWORD2"
      ],
      "authors": [
        {
            "name": "default-template-name1",
            "email": "default-template-email1",
            "role": "default-template-role1"
        },
        {
            "name": "default-template-name2",
            "email": "default-template-email2",
            "role": "default-template-role2"
        }
      ],
      "provide": {
        "package1": "default-template-provided-package1",
        "package2": "default-template-provided-package2"
      },
      "suggest": {
        "package1": "default-template-suggested-package1",
        "package2": "default-template-suggested-package2"
      },
      "support": {
        "type1": "default-template-support-type1",
        "type2": "default-template-support-type2"
      },
      "autoload": {
        "psr-0": {
            "DefaultNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
            "DefaultNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
            "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
            "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      },
      "autoload-dev": {
        "psr-0": {
            "DefaultNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
            "DefaultNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
            "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
            "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      },
      "require": {
        "requirement1": "default-template-required-package1"
      },
      "require-dev": {
        "requirement1": "default-template-required-dev-package1"
      },
      "scripts": {
        "default-script-1": [
            "default-script1-command_1",
            "default-script1-command_2"
        ],
        "default-script-2": [
            "default-script2-command_1",
            "default-script2-command_2"
        ]
      }
    }

    """
    When I execute composercm create with "vendor/name"
    Then configuration file should contains:
    """
    {
      "name": "vendor/name",
      "type": "default-template-type",
      "license": "default-template-license",
      "version": "default-template-version",
      "description": "default-template-description"
    }
    """
    And configuration file should contains:
    """
    {
      "keywords": [
        "DEFAULT-TEMPLATE-KEYWORD1",
        "DEFAULT-TEMPLATE-KEYWORD2"
      ]
    }
    """
    And configuration file should contains:
    """
    {

      "authors": [
        {
            "name": "default-template-name1",
            "email": "default-template-email1",
            "role": "default-template-role1"
        },
        {
            "name": "default-template-name2",
            "email": "default-template-email2",
            "role": "default-template-role2"
        }
      ]
    }
    """
    And configuration file should contains:
    """
    {

      "provide": {
        "package1": "default-template-provided-package1",
        "package2": "default-template-provided-package2"
      }
    }
    """
    And configuration file should contains:
    """
    {

      "suggest": {
        "package1": "default-template-suggested-package1",
        "package2": "default-template-suggested-package2"
      }
    }
    """
    And configuration file should contains:
    """
    {

      "support": {
        "type1": "default-template-support-type1",
        "type2": "default-template-support-type2"
      }
    }
    """
    And configuration file should contains:
    """
    {

      "autoload": {
        "psr-0": {
          "DefaultNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
          "DefaultNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
          "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {

      "autoload-dev": {
        "psr-0": {
          "DefaultNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
          "DefaultNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
          "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {

      "require": {
        "requirement1": "default-template-required-package1"
      }
    }
    """
    And configuration file should contains:
    """
    {

      "require-dev": {
        "requirement1": "default-template-required-dev-package1"
      }
    }
    """
    And configuration file should contains:
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
        ]
      }
    }
    """

  Scenario: Full configuration with added values
    Given I will use configuration template at "./template/template.json" with:
    """
    {
      "name": "default-template-name",
      "type": "default-template-type",
      "license": "default-template-license",
      "version": "default-template-version",
      "description": "default-template-description",
      "keywords": [
        "DEFAULT-TEMPLATE-KEYWORD1",
        "DEFAULT-TEMPLATE-KEYWORD2"
      ],
      "authors": [
        {
            "name": "default-template-name1",
            "email": "default-template-email1",
            "role": "default-template-role1"
        },
        {
            "name": "default-template-name2",
            "email": "default-template-email2",
            "role": "default-template-role2"
        }
      ],
      "provide": {
        "package1": "default-template-provided-package1",
        "package2": "default-template-provided-package2"
      },
      "suggest": {
        "package1": "default-template-suggested-package1",
        "package2": "default-template-suggested-package2"
      },
      "support": {
        "type1": "default-template-support-type1",
        "type2": "default-template-support-type2"
      },
      "autoload": {
        "psr-0": {
            "DefaultNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
            "DefaultNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
            "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
            "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      },
      "autoload-dev": {
        "psr-0": {
            "DefaultNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
            "DefaultNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
            "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
            "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      },
      "require": {
        "requirement1": "default-template-required-package1"
      },
      "require-dev": {
        "requirement1": "default-template-required-dev-package1"
      },
      "scripts": {
        "default-script-1": [
            "default-script1-command_1",
            "default-script1-command_2"
        ],
        "default-script-2": [
            "default-script2-command_1",
            "default-script2-command_2"
        ]
      }
    }

    """
    When I execute composercm create with "vendor/name" and following options:
    """
    --keyword my_keyword --keyword my_keyword2 --author author#email#role --author name2 --author name3#email3 --provided-package name/A#url1 --provided-package name2/B#url2 --provided-package name/C#url3 --suggested-package "name/A#description 1" --suggested-package "name2/B#description 2" --suggested-package "name/C#description 3" --support "typeA#urlA" --support "typeB#urlB" --support "typeC#urlC" --autoload-psr0 "vendor1\\Test#src1" --autoload-psr4 "\\vendor2\\Test\\#src2" --autoload-psr0 "vendor1\\Test2#src3" --autoload-dev-psr0 "vendor1\\Test#src1" --autoload-dev-psr4 "vendor2\\Test#src2" --autoload-dev-psr0 "vendor1\\Test2#src3" --require "vendor1/A#v1.3.0" --require "vendor2/B#>=2.0.0" --require "vendor1/C#~3.2" --require-dev "vendor1/A#v1.3.0" --require-dev "vendor2/B#>=2.0.0" --require-dev "vendor1/C#~3.2" --script "name1#command1" --script "name2#command1" --script "name1#command2"
    """
    Then configuration file should contains:
    """
    {
      "keywords": [
        "DEFAULT-TEMPLATE-KEYWORD1",
        "DEFAULT-TEMPLATE-KEYWORD2",
        "my_keyword",
        "my_keyword2"
      ]
    }
    """
    And configuration file should contains:
    """
    {

      "authors": [
        {
            "name": "default-template-name1",
            "email": "default-template-email1",
            "role": "default-template-role1"
        },
        {
            "name": "default-template-name2",
            "email": "default-template-email2",
            "role": "default-template-role2"
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
    And configuration file should contains:
    """
    {

      "provide": {
        "package1": "default-template-provided-package1",
        "package2": "default-template-provided-package2",
        "name/A": "url1",
        "name2/B": "url2",
        "name/C": "url3"
      }
    }
    """
    And configuration file should contains:
    """
    {

      "suggest": {
        "package1": "default-template-suggested-package1",
        "package2": "default-template-suggested-package2",
        "name/A": "description 1",
        "name2/B": "description 2",
        "name/C": "description 3"
      }
    }
    """
    And configuration file should contains:
    """
    {

      "support": {
        "type1": "default-template-support-type1",
        "type2": "default-template-support-type2",
        "typeA": "urlA",
        "typeB": "urlB",
        "typeC": "urlC"
      }
    }
    """
    And configuration file should contains:
    """
    {

      "autoload": {
        "psr-0": {
          "DefaultNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
          "DefaultNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2",
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2",
          "\\vendor2\\Test\\": "src2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {

      "autoload-dev": {
        "psr-0": {
          "DefaultNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
          "DefaultNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2",
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2",
          "vendor2\\Test": "src2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {

      "require": {
        "requirement1": "default-template-required-package1",
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """
    And configuration file should contains:
    """
    {

      "require-dev": {
        "requirement1": "default-template-required-dev-package1",
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """
    And configuration file should contains:
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