#!/bin/sh
PARTITIONDAY=$(date +%Y%m%d -d "yesterday")
YESTERDAY=`date +%Y-%m-%d -d "yesterday"`
DB='aguila_db'
MONTH=$(date +%Y_%m -d "yesterday")
DIR_BACKUP='/home/backup/aguila/'$MONTH

[ -d $DIR_BACKUP ] || mkdir $DIR_BACKUP

#backup database aguila
pg_dump -h localhost -U postgres $DB --exclude-schema=partitions -Z4 -Fc > $DIR_BACKUP'/'$DB'_'$YESTERDAY 

#backup partitions database aguila
pg_dump -h localhost -U postgres $DB -t 'partitions.cellphone_statuses_'$PARTITIONDAY  -t 'partitions.gps_statuses_'$PARTITIONDAY -Z4 -Fc > $DIR_BACKUP'/'$DB'_partitions_'$YESTERDAY 

if ! grep -qs '/mnt/backup/aguila ' /proc/mounts; then
    mount -t cifs -o username=respaldoaguila,password=4gu1LA //192.168.0.71/respaldo_aguila/aguila /mnt/backup/aguila
fi

if grep -qs '/mnt/backup/aguila ' /proc/mounts; then
 rsync -r --ignore-existing /home/backup/aguila/ /mnt/backup/aguila/
fi

#way to restore
#pg_restore -h localhost -U postgres -j 4 -d $DB < aguila_db_2018-10-17
