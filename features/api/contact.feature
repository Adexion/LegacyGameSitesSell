Feature:
  I should be able to send contact message

  Scenario: Send valid contact message
    Given As logged user
    And I store token to request
    And the request body is:
    """
    {
      "name": "testowe",
      "email": "test@testowy.pl",
      "type": "support",
      "subject": "testowy",
      "message": "test testowy testowiec testera"
    }
    """
    When I request "/v1/contact" using HTTP "POST"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "token": "@variableType(string)"
    }
    """

  Scenario: Send contact with empty fields
    When I request "/v1/contact" using HTTP "POST"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "name": "Ta wartość nie powinna być pusta.",
        "email": "Ta wartość nie powinna być pusta.",
        "type": "Ta wartość nie powinna być pusta.",
        "subject": "Ta wartość nie powinna być pusta.",
        "message": "Ta wartość nie powinna być pusta."
    }
    """

  Scenario: Send contact with no valid data
    Given the request body is:
    """
    {
      "name": "",
      "email": "test",
      "type": "invalid",
      "subject": "",
      "message": ""
    }
    """
    When I request "/v1/contact" using HTTP "POST"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "name": "Ta wartość nie powinna być pusta.",
        "email": "Ta wartość nie jest prawidłowym adresem email.",
        "type": "Ta wartość jest nieprawidłowa.",
        "subject": "Ta wartość nie powinna być pusta.",
        "message": "Ta wartość nie powinna być pusta."
    }
    """
