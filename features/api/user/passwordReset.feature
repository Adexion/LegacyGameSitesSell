Feature:
  I should be able to get email with reset token

  Background:
    Given A register user

  Scenario: I try to get email reset password
    Given the request body is:
    """
    {
        "username": "test"
    }
    """
    When I request "/v1/user/reset" using HTTP "POST"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
         "status": 1
    }
    """

  Scenario: I try to get email reset password
    Given set password reset token as testTest123
    And the request body is:
    """
    {
        "token": "testTest123",
        "password": {
            "first": "test123",
            "second": "test123"
        }
    }
    """
    When I request "/v1/user/password" using HTTP "PUT"
    Then the response code is 204

  Scenario: Reset password with two different passwords
    Given set password reset token as testTest123
    And the request body is:
    """
    {
        "token": "testTest123",
        "password": {
            "first": "test123",
            "second": "test"
        }
    }
    """
    When I request "/v1/user/password" using HTTP "PUT"
    Then the response code is 400
    Then the response body contains JSON:
    """
    {
          "password[first]": "Ta wartość jest nieprawidłowa."
    }
    """

  Scenario: Given wrong token
    Given set password reset token as testTest123
    And the request body is:
    """
    {
        "token": "test",
        "password": {
            "first": "test123",
            "second": "test"
        }
    }
    """
    When I request "/v1/user/password" using HTTP "PUT"
    Then the response code is 400
    Then the response body contains JSON:
    """
    {
          "token": "Ta wartość jest nieprawidłowa."
    }
    """

  Scenario: Try without data
    When I request "/v1/user/password" using HTTP "PUT"
    Then the response code is 400
    Then the response body contains JSON:
    """
    {
          "token": "Ta wartość jest nieprawidłowa."
    }
    """
