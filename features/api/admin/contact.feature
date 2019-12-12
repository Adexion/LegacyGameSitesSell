Feature:
  As logged admin user
  I should be able to use article endpoints

  Scenario:
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
    And I store token
    Given As logged admin user
    And I store token to request
    And the request body is:
    """
    {
        "id": 1,
        "message": "response"
    }
    """
    When I request "/v1/admin/contact" using HTTP "POST"
    Then the response code is 204
    Given I store token to request
    When I request "/v1/admin/contact" using HTTP "GET"
    Then the response code is 200
    And the response body contains JSON:
    """
    [
        {
            "token": "@variableType(string)",
            "name": "testowe"
        }
    ]
    """
    When I request "/v1/admin/contact" using HTTP "GET" using stored param as token
    Then the response code is 200
    And the response body contains JSON:
    """
    [
         {
             "contactId": 1,
             "name": "testowe",
             "email": "test@testowy.pl",
             "type": "support",
             "subject": "testowy",
             "message": "test testowy testowiec testera",
             "token": "@variableType(string)",
             "status": "2",
             "reCaptcha": null
         },
         {
             "contactId": 2,
             "name": "testowe",
             "email": "test@testowy.pl",
             "type": "support",
             "subject": "testowy",
             "message": "test testowy testowiec testera",
             "token": "@variableType(string)",
             "status": "2",
             "reCaptcha": null
         }
    ]
    """
