#! /bin/sh

echo Use database:
read _DBNAME

echo Drop/Create $_DBNAME
mysql -hlocalhost -uerniuniu -perniuniu <<EOFMYSQL
DROP DATABASE IF EXISTS $_DBNAME;
CREATE DATABASE $_DBNAME;
EOFMYSQL

echo Load $1
mysql -hlocalhost -uerniuniu -perniuniu $_DBNAME < $1

logsave -a loadDump.log printf "Loaded dump [%s] into [%s]" $1 $_DBNAME
echo ""
