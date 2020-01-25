Feature:
  As logged user
  If I send request by MicroSMS
  I should be able to add money to my wallet

  Scenario: I try to add money to my wallet
    Given As logged user
    And I store token to request
    And set price 1.23 for phone number 71480 and amount 1.23
    And the request body is:
    """
    {
      "smsCode": "VALID_CODE"
    }
    """
    When I request "/v1/prepaid/sms" using HTTP "POST"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "cash": 3.23
    }
    """

  Scenario: I send invalid format smsCode
    Given As logged user
    And I store token to request
    And set price 1.23 for phone number 71480
    And the request body is:
    """
    {
      "smsCode": "INVALID_FORMAT_VALUE"
    }
    """
    When I request "/v1/prepaid/sms" using HTTP "POST"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
      "smsCode": "Nieprawidłowy format kodu sms."
    }
    """

  Scenario: I send not exist smsCode
    Given As logged user
    And I store token to request
    And set price 1.23 for phone number 71480
    And the request body is:
    """
    {
      "smsCode": "INVALID_VALUE"
    }
    """
    When I request "/v1/prepaid/sms" using HTTP "POST"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
      "smsCode": "Przesłany kod jest nieprawidłowy."
    }
    """
