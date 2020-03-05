Feature:
  I should be able to get articles

  @dev
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
    Then the response code is 204
    When I request "/v1/article" using HTTP "GET"
    Then the response code is 200
    And the response body contains JSON:
    """
    [
        {
            "id": 1,
            "title": "test test",
            "subhead": "test2 test2",
            "image": "testTest123",
            "text": "testowy text",
            "shortText": "short text"
        }
    ]
    """
