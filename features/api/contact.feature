Feature:
  As logged user
  I should be able to send contact message

  Scenario:
    Given As logged user
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
        "ticket": "@variableType(string)"
    }
    """
