#! /bin/sh

set -e

echo s3://erniuniu-database-backup/$1/$2.tgz
s3cmd get s3://erniuniu-database-backup/$1/$2.tgz
tar -xzf $2.tgz
../loadDump.sh $2
