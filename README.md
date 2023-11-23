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

### Find active booking
http://localhost:8080/active-bookings/hotel/{hotelId}/room/{roomNumber}

### Run tests - unit (phpUnit) and functional (Behat)
```bash
#outside docker container
$ make test

#inside fpm container
$ make run-tests
```

