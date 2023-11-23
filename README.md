# Booking
In order to get started spin up the containers, and install project dependencies.

```bash
# Docker containers up
# Install project dependencies
# Doctrine migrations
# Store active bookings from PMS faker

$ make init
```
### Check application health
http://localhost:8080/health-check

### Get active booking
http://localhost:8080/active-bookings/hotel/{hotel}/room/{room}

Required params:

- hotel -> Stay hotel ID
- room -> Number of hotel room

Method: GET

### Run tests - unit (phpUnit) and functional (Behat)
```bash
#outside docker container
$ make test

#inside fpm container
$ make run-tests
```

