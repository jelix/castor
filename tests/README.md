Unit tests
===========


to launch unit tests, you can use the php cli you have installed on your system.
Or you can use the docker file provided here.

```bash
# set the php version: 7.4, 8.1 or 8.2
export PHP_VERSION=8.1
# build the container  
./launchtests build

# launch tests
./launchtests tests
```