#!/bin/bash

USE_ZEND_ALLOC=0 valgrind --leak-check=full --show-reachable=yes --track-origins=yes --log-file=/tmp/leak.log php -d extension=swow game/swow_worker.php
