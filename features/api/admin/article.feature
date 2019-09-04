Feature:
  As logged admin user
  I should be able to use article endpoints

  Scenario:
    Given As logged admin user
    And I store token to request
    And the request body is:
    """
    {
        "title": "test test",
        "subhead": "test2 test2",
        "image": "testTest123",
        "text": "testowy text",
        "shortText": "short text"
    }
    """
    When I request "/v1/admin/article" using HTTP "POST"
    Then the response code is 200
    And the response body is an empty JSON object
    Given I store token to request
    And the request body is:
    """
    {
        "id": 1,
        "title": "test test test test"
    }
    """
    When I request "/v1/admin/article" using HTTP "PUT"
    Then the response code is 200
    And the response body is an empty JSON object
    Given I store token to request
    When I request "/v1/admin/article?id=1" using HTTP "DELETE"
    Then the response code is 200
    And the response body is an empty JSON object
