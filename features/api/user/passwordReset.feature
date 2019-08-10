Feature:
  I should be able to get email with reset token

  Background:
    Given A register user

  Scenario: I try to reset password
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
