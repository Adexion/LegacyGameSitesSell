Feature:
  I should be able to get player data

  Scenario: Get player avatar
    When I request "/v1/player/avatar?username=adexion" using HTTP "GET"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "avatar": "@variableType(string)"
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
