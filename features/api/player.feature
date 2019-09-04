Feature:
  I should be able to get player data

  Scenario: Get player avatar
    When I request "/v1/player/avatar?username=adexion" using HTTP "GET"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "link": "https://crafatar.com/avatars/48fbb5a077394d8da623ecff6f87ad79"
    }
    """

  Scenario: Error without user name
    When I request "/v1/player/avatar" using HTTP "GET"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "username": "Pole nie może być puste."
    }
    """
