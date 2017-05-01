Feature: As user
  In order to create composer configuration file from a template
  I should be able to use the command line

  @yo
  Scenario: Use a template file
    Given I create a template file at "./template/template.json" with:
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
    --template ./template/template.json
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

  Scenario: Use a template path
    Given I have the folder "./template/test"
    And I create a template file at "./template/test/composer.json" with:
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
    When I execute composercm create with "pk_namespace\\pk_name" and following options:
    """
    --template ./template/template.json
    """
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