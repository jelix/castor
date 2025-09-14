Unit tests
===========


to launch unit tests, you can use the php cli you have installed on your system.
Or you can use the docker file provided here.

```bash
# set the php version: 8.2, 8.3 or 8.4
export PHP_VERSION=8.2
# build the container  
./launchtests build

# launch tests
./launchtests tests
```