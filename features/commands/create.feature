Feature: As user
  In order to create composer configuration file
  I should be able to use the command line

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

  Scenario: Multiple keywords
    Given I execute composercm create with following options:
    """
    --keyword my_keyword --keyword my_keyword2
    """
    Then configuration file should contains:
    """
    {
      "keywords": ["my_keyword", "my_keyword2"]
    }
    """

  Scenario: Multiple authors
    Given I execute composercm create with following options:
    """
    --author author#email#role --author name2 --author name3#email3
    """
    Then configuration file should contains:
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

  Scenario: Multiple provided packages
    Given I execute composercm create with following options:
    """
    --provided-package name/A#url1 --provided-package name2/B#url2 --provided-package name/C#url3
    """
    Then configuration file should contains:
    """
    {
      "provide": {
        "name/A": "url1",
        "name2/B": "url2",
        "name/C": "url3"
      }
    }
    """

  Scenario: Multiple suggested packages
    Given I execute composercm create with following options:
    """
    --suggested-package "name/A#description 1" --suggested-package "name2/B#description 2" --suggested-package "name/C#description 3"
    """
    Then configuration file should contains:
    """
    {
      "suggest": {
        "name/A": "description 1",
        "name2/B": "description 2",
        "name/C": "description 3"
      }
    }
    """

  Scenario: Multiple supports
    Given I execute composercm create with following options:
    """
    --support "type1#url1" --support "type2#url2" --support "type3#url3"
    """
    Then configuration file should contains:
    """
    {
      "support": {
        "type1": "url1",
        "type2": "url2",
        "type3": "url3"
      }
    }
    """

  Scenario: Multiple autoload (mixing PSR-0 and PSR-4)
    Given I execute composercm create with following options:
    """
    --autoload-psr0 "vendor1\\Test#src1" --autoload-psr4 "\\vendor2\\Test\\#src2" --autoload-psr0 "vendor1\\Test2#src3"
    """
    Then configuration file should contains:
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

  Scenario: Multiple autoload dev (mixing PSR-0 and PSR-4)
    Given I execute composercm create with following options:
    """
    --autoload-dev-psr0 "vendor1\\Test#src1" --autoload-dev-psr4 "\\vendor2\\Test\\#src2" --autoload-dev-psr0 "vendor1\\Test2#src3"
    """
    Then configuration file should contains:
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


  Scenario: Multiple require (mixing PSR-0 and PSR-4)
    Given I execute composercm create with following options:
    """
    --require "vendor1/A#v1.3.0" --require "vendor2/B#>=2.0.0" --require "vendor1/C#~3.2"
    """
    Then configuration file should contains:
    """
    {
      "require": {
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """

  Scenario: Multiple require dev (mixing PSR-0 and PSR-4)
    Given I execute composercm create with following options:
    """
    --require-dev "vendor1/A#v1.3.0" --require-dev "vendor2/B#>=2.0.0" --require-dev "vendor1/C#~3.2"
    """
    Then configuration file should contains:
    """
    {
      "require-dev": {
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """

  Scenario: Multiple scripts
    Given I execute composercm create with following options:
    """
    --script "name1#command1" --script "name2#command1" --script "name1#command2"
    """
    Then configuration file should contains:
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
