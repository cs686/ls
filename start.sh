#!/bin/sh
WORK_DIR=/var/www/html

if [ ! -n "${WORK_DIR}" ] ;then
    WORK_DIR="."
fi
php artisan swoole:http start
echo "Starting watch..."

LOCKING=0

inotifywait -mrq -e modify,delete,create,move ${WORK_DIR} |
    while read path action file; do
        if [[ ${file##*.} == 'php' ]]; then
            if [ ${LOCKING} -eq 1 ] ;then
                echo "Reloading, skipped."
                continue
            else
                LOCKING=1
                echo "File ${file} has been modified."
                php artisan swoole:http reload
                LOCKING=0
            fi 
        else
            echo "change none php file."
            LOCKING=0
        fi
    done

exit 0