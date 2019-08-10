Feature:
  As logged user
  I should be able to register account

  Scenario: Login to launcher
    Given the request body is:
    """
    {
        "username": "test",
        "email": "test@testowy.pl",
        "rules": true,
        "password": {
            "first": "testTest123",
            "second": "testTest123"
        }
    }
    """
    When I request "/v1/user/register" using HTTP "POST"
    Then the response code is 201
    Given the request body is:
    """
    {
        "username": "test",
        "password": "testTest123"
    }
    """
    When I request "/v1/user/launcher/login" using HTTP "POST"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "accessToken":"@variableType(string)",
        "clientToken":"@variableType(string)",
        "selectedProfile": {
            "agent":"minecraft",
            "id":"@variableType(string)",
            "userId":"@variableType(string)",
            "name":"test",
            "createdAt":"@variableType(integer)",
            "legacyProfile":false,
            "suspended":false,
            "tokenId":"@variableType(string)",
            "paid":true,
            "migrated":false
        },
        "availableProfiles": [
            {
                "agent":"minecraft",
                "id":"@variableType(string)",
                "userId":"@variableType(string)",
                "name":"test",
                "createdAt":"@variableType(integer)",
                "legacyProfile":false,
                "suspended":false,
                "tokenId":"@variableType(string)",
                "paid":true,
                "migrated":false
            }
        ],
        "banned":false
    }
    """
