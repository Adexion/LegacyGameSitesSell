Feature:
  As logged user
  I should be able to register account

  Scenario: I try to register new account
    Given the request body is:
    """
    {
        "username": "test",
        "email": "test@testowy.pl",
        "rules": true,
        "password": {
            "first": "testTest123",
            "second": "testTest123"
        }
    }
    """
    When I request "/v1/user/register" using HTTP "POST"
    Then the response code is 201
    And the response body is an empty JSON object
    Given the request body is:
    """
    {
        "username": "test",
        "password": "testTest123"
    }
    """
    When I request "/v1/user/login" using HTTP "POST"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "token": "@variableType(string)"
    }
    """

  Scenario: Invalid error while empty request
    When I request "/v1/user/register" using HTTP "POST"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "username": "Ta wartość nie powinna być pusta.",
        "email": "Ta wartość nie powinna być pusta.",
        "password[first]": "Ta wartość nie powinna być pusta.",
        "rules": "Proszę zaznaczyć wymagane zgody."
    }
    """

  Scenario: Invalid error while invalid fields request
    Given the request body is:
    """
    {
        "username": null,
        "email": "test",
        "rules": false,
        "password": ""
    }
    """
    When I request "/v1/user/register" using HTTP "POST"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "username": "Ta wartość nie powinna być pusta.",
        "email": "Ta wartość nie jest prawidłowym adresem email.",
        "password[first]": "Ta wartość jest nieprawidłowa.",
        "rules": "Proszę zaznaczyć wymagane zgody."
    }
    """
