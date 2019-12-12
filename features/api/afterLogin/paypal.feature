Feature:
  As logged user
  If I send request by Paypal
  I should be able to add money to my wallet

  Scenario: I try to add money to my wallet after paypal execution
    Given As logged user
    And I store token to request
    And the request body is:
    """
    {
      "paymentId": "VALID_PAYMENT_ID",
      "payerId": "VALID_PAYER_ID"
    }
    """
    When I request "/v1/prepaid/paypal" using HTTP "POST"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "cash": "@variableType(double)"
    }
    """

  Scenario: Error on bad credentials
    Given As logged user
    And I store token to request
    And the request body is:
    """
    {
        "paymentId": "INVALID_PAYMENT_ID",
        "payerId": "INVALID_PAYER_ID"
    }
    """
    When I request "/v1/prepaid/paypal" using HTTP "POST"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "paymentId": "Podana płatność nie istnieje lub wystąpił problem po stronie serwera."
    }
    """

