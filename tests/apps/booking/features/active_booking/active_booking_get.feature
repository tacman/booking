Feature: Api status
  In order to know the room availability
  As a user api
  I want to get and active booking data by hotel and room or get not found message

  Scenario: Get active booking
    Given I send a GET request to "/active-bookings/hotel/d9128faa-fb1b-43fb-8f50-a192e290983b/room/299"
    Then the response content should be:
    """
    {
      "bookingId": "d6fd01f3-37bb-44ac-9194-d32ff2e81d12",
      "hotel": "d9128faa-fb1b-43fb-8f50-a192e290983b",
      "locator": "655B479CECEAF",
      "room": "299",
      "checkIn": "2023-11-20",
      "checkOut": "2023-12-03",
      "numberOfNights": 13,
      "totalPax": 1,
      "guests": [
        {
          "name": "Asier",
          "lastname": "Chapa",
          "birthdate": "1945-08-06",
          "passport": "NP-1320834-ZS",
          "country": "NP",
          "age": 78
        }
      ]
    }
    """

  Scenario: Active booking but not found
    Given I send a GET request to "/active-bookings/hotel/d9128faa-fb1b-43fb-8f50-a192e290913b/room/2999999"
    Then the response content should be:
    """
    {
      "error": "Active booking for hotel d9128faa-fb1b-43fb-8f50-a192e290913b and room 2999999 not found",
      "reason": "resource.not.found"
    }
    """
