Feature:
  As logged admin user
  I should be able to use itemList endpoints

  Scenario:
    Given As logged admin user
    And I store token to request
    And the request body is:
    """
    {
        "name": "test test",
        "description": "test2 test2",
        "icon": "testTest123",
        "sliderImage": "testTest123",
        "price": 10.00,
        "promotion": 0.1
    }
    """
    When I request "/v1/admin/item/list" using HTTP "POST"
    Then the response code is 204
    Given I store token to request
    And the request body is:
    """
    {
        "id": 1,
        "name": "test test test test"
    }
    """
    When I request "/v1/admin/item/list" using HTTP "PUT"
    Then the response code is 204
    Given I store token to request
    When I request "/v1/admin/item/list?id=1" using HTTP "DELETE"
    Then the response code is 204
