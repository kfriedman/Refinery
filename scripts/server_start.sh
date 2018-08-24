#!/bin/bash

# Change to scripts directory
cd "$( dirname "${BASH_SOURCE[0]}" )"

# Run server initialization tasks
php -d error_log=/var/log/php_errors.log ../src/index.php initialize &

# Start Queue workers
for i in `seq 1 1`;
do
    php -d error_log=/var/log/php_errors.log ../src/index.php queue-worker &
done
