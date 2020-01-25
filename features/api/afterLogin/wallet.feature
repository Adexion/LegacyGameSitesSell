Feature:
  As logged user
  I should be able to get my wallet status

  Scenario: I try to get money from my wallet
    Given As logged user
    And I store token to request
    When I request "/v1/prepaid/status" using HTTP "GET"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "cash": 2
    }
    """

