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
docker-compose exec php-queue bash
cd queue-worker
php server.php
```
# Config
**src/config/.env**
<br/>
- MAX_CONN : https://www.swoole.co.uk/docs/modules/swoole-server/configuration#max_conn
- QUEUE_TABEL_SIZE: This table is responsible to store queues payloads. https://www.swoole.co.uk/docs/modules/swoole-table-construct
- IGNORE_NO_LISTENERS: 0 or 1 , if 0 the server will not dispatch the queues if there is no >= 1 listener.
- BUFFER_OUTPUT_SIZE: https://www.swoole.co.uk/docs/modules/swoole-server/configuration#buffer_output_size
- REACTOR_NUM: https://www.swoole.co.uk/docs/modules/swoole-server/configuration#reactor_num
- WORKER_NUM: https://www.swoole.co.uk/docs/modules/swoole-server/configuration#worker_num
# How to use it / examples
See the examples/ folder
# Observers
## PHP observer
**app/Parser/Observer/ParserObserver.php**
You can write PHP code to handle received queues and parse them before saving them to the storage.
Also, you can handle the queue message before sending them to listeners.
## Extend to Lua
You can write LUA code to handle received queues and parse them before saving them to the storage.
Also, you can handle the queue message before sending them to listeners.
