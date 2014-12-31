#! /bin/sh

echo [Starting synchronizing database]

echo "\n>>> Downloading s3://erniuniu-database-backup/$1/$2.tgz"
cd /home/vagrant/production_databases
s3cmd get s3://erniuniu-database-backup/$1/$2.tgz

echo "\n>>> Decompressing $2.tgz"
tar -xzf $2.tgz

/home/vagrant/load_database.sh $2

echo "\n[Finished synchronizing database]\n"
