# QPHP
QPHP is a simple, fast work queue server extendable to LUA scripts. Built using PHP & SWOOLE.
# RUN it
First, build the image :
```
docker-compose build
```
Run : 
```
docker-compose up
docker-compose exec PHP queue-bash
cd queue-worker
php server.php
```
# How to use it / examples
See the examples/ folder
# Observers
## PHP observer
**app/Parser/Observer/ParserObserver.php**
You can write PHP code to handle received queues and parse it before will be saved to the storage.
Also, you can handle the queue message before it will be sent to listeners.
## Extend to Lua
You can write LUA code to handle received queues and parse it before will be saved to the storage.
Also, you can handle the queue message before it will be sent to listeners.
