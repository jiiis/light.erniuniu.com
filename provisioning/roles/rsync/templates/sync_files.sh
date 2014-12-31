#! /bin/sh

sync_files () {
    echo "[Starting synchronizing $1/$2]\n"

    rsync -rvzh ubuntu@light.erniuniu.com:/var/www/light.erniuniu.com/shared/$1/$2 /var/www/light.erniuniu.com.local/$1

    echo "\n[Finished synchronizing $1/$2]\n"
}

if test "$1" = "vendor"; then
    sync_files "src" "vendor"
elif test "$1" = "images"; then
    sync_files "src/public/frontend" "images"
else
    sync_files "src" "vendor"
    sync_files "src/public/frontend" "images"
fi
