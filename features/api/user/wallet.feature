Feature:
  As logged user
  I should be able to get my wallet status

  Scenario: I try to add money to my wallet
    Given As logged user
    And I store token to request
    When I request "/v1/cash" using HTTP "GET"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "cash": 0
    }
    """

