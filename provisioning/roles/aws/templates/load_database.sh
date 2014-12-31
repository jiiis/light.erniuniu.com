#! /bin/sh

echo "\n>>> Dropping and creating database {{ mysql_database }}"
mysql -h{{ mysql_host }} -u{{ mysql_username }} -p{{ mysql_password }} <<EOFMYSQL
DROP DATABASE IF EXISTS {{ mysql_database }};
CREATE DATABASE {{ mysql_database }};
EOFMYSQL

echo "\n>>> Loading $1"
mysql -h{{ mysql_host }} -u{{ mysql_username }} -p{{ mysql_password }} {{ mysql_database }} < $1

#echo "\n>>> Updating log"
#logsave -a loadDump.log printf "Loaded dump [%s] into [%s]" $1 {{ mysql_database }}
