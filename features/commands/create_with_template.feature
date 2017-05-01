Feature: As user
  In order to create composer configuration file from a template
  I should be able to use the command line

  Scenario: Use a template file
    Given I will use template at "./template/template.json" with:
    """
    {
      "type": "template_type",
      "license": "template_license",
      "version": "template_version",
      "description": "template_description"
    }

    """
    When I execute composercm create with "pk_namespace\\pk_name"
    Then configuration file should be:
    """
    {
      "name": "pk_namespace\\pk_name",
      "type": "template_type",
      "license": "template_license",
      "version": "template_version",
      "description": "template_description"
    }
    """

  Scenario: Use a template path
    Given I have the folder "./template/test"
    And I will use template at "./template/test/composer.json" with:
    """
    {
      "type": "template_type",
      "license": "template_license",
      "version": "template_version",
      "description": "template_description"
    }
    """
    When I execute composercm create with "pk_namespace\\pk_name" and following options:
    """
    --template ./template/test
    """
    Then configuration file should be:
    """
    {
      "name": "pk_namespace\\pk_name",
      "type": "template_type",
      "license": "template_license",
      "version": "template_version",
      "description": "template_description"
    }
    """

  Scenario: Use a bad template file
    Given I have no file at "./template/template.json"
    And I will use template at "./template/template.json"
    When I execute composercm create with "pk_namespace\\pk_name"
    Then composercm output should be:
    """
    {
      "name": "pk_namespace\\pk_name",
      "type": "template_type",
      "license": "template_license",
      "version": "template_version",
      "description": "template_description"
    }
    """