Feature:
  As logged user
  If I send request by Paypal
  I should be able to add money to my wallet

  Scenario: I try to add money to my wallet
    Given As logged user
    And I store token to request
    And the request body is:
    """
    {
      "paymentID": "VALID_PAYMENT_ID",
      "payerID": "VALID_PAYER_ID"
    }
    """
    When I request "/v1/payment/paypal" using HTTP "POST"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "cash": "@variableType(integer)"
    }
    """

  Scenario: Error on bad credentials
    Given As logged user
    And I store token to request
    And the request body is:
    """
    {
        "paymentID": "INVALID_PAYMENT_ID",
        "payerID": "INVALID_PAYER_ID"
    }
    """
    When I request "/v1/payment/paypal" using HTTP "POST"
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "paymentID": "Podana płatność nie istnieje lub wystąpił problem po stronie serwera."
    }
    """

