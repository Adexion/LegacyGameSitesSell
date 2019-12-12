Feature:
  As logged user
  I should be able to change my password

  Scenario: I try to update password
    Given As logged user
    And I store token to request
    And the request body is:
    """
    {
        "password": {
            "first": "test123test",
            "second": "test123test"
        }
    }
    """
    When I request "/v1/user" using HTTP "PUT"
    Then the response code is 204
    Given the request body is:
    """
    {
        "username": "test",
        "password": "test123test"
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

  Scenario:  Given wrong password field
    Given As logged user
    And I store token to request
    And the request body is:
    """
    {
        "password": ""
    }
    """
    When I request "/v1/user" using HTTP "PUT"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "password[first]": "Ta wartość jest nieprawidłowa."
    }
    """

  Scenario:  Given two different password
    Given As logged user
    And I store token to request
    And the request body is:
    """
    {
        "username": "test",
        "password": "test123test"
    }
    """
    When I request "/v1/user" using HTTP "PUT"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "password[first]": "Ta wartość jest nieprawidłowa."
    }
    """
