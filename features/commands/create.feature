Feature: As user
  In order to create composer configuration file
  I should be able to use the command line

  @yo
  Scenario: Default
    Given I execute composercm create with "pk_namespace\\pk_name"
    Then configuration file should be:
    """
    {
      "name": "pk_namespace\\pk_name"
    }
    """

  Scenario: Specify location
    Given I have the folder "./build/test"
    When I execute composercm create with "pk_namespace\\pk_name" and "./build/test"
    Then I should have a configuration file at "./build/test"

  Scenario: Basic configuration file
    Given I execute composercm create with following options:
    """
    --description "pk description" --type my_type --license my_license --package-version 1.2.3
    """
    Then configuration file should contains:
    """
    {
      "type": "my_type",
      "license": "my_license",
      "version": "1.2.3",
      "description": "pk description"
    }
    """

  Scenario: Full configuration
    Given I execute composercm create with following options:
    """
    --description "pk description" --type my_type --license my_license --package-version 1.2.3 --keyword my_keyword --keyword my_keyword2 --author author#email#role --author name2 --author name3#email3 --provided-package name/A#url1 --provided-package name2/B#url2 --provided-package name/C#url3 --suggested-package "name/A#description 1" --suggested-package "name2/B#description 2" --suggested-package "name/C#description 3" --support "type1#url1" --support "type2#url2" --support "type3#url3" --autoload-psr0 "vendor1\\Test#src1" --autoload-psr4 "\\vendor2\\Test\\#src2" --autoload-psr0 "vendor1\\Test2#src3" --autoload-dev-psr0 "vendor1\\Test#src1" --autoload-dev-psr4 "\\vendor2\\Test\\#src2" --autoload-dev-psr0 "vendor1\\Test2#src3" --require "vendor1/A#v1.3.0" --require "vendor2/B#>=2.0.0" --require "vendor1/C#~3.2" --require-dev "vendor1/A#v1.3.0" --require-dev "vendor2/B#>=2.0.0" --require-dev "vendor1/C#~3.2" --script "name1#command1" --script "name2#command1" --script "name1#command2"
    """
    Then configuration file should contains:
    """
    {
      "type": "my_type",
      "license": "my_license",
      "version": "1.2.3",
      "description": "pk description"
    }
    """
    And configuration file should contains:
    """
    {
      "keywords": ["my_keyword", "my_keyword2"]
    }
    """
    And configuration file should contains:
    """
    {
      "authors": [
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
        "type1": "url1",
        "type2": "url2",
        "type3": "url3"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "autoload": {
        "psr-0": {
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
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
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\vendor2\\Test\\": "src2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {
      "require": {
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
