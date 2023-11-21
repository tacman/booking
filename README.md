# Booking
In order to get started spin up the containers, and install project dependencies.

```bash
$ make init
```
###Check application health
http://localhost:8080/health-check

###Run tests
```bash
#outside docker container
$ make test

#inside fpm container
$ make run-tests
```

