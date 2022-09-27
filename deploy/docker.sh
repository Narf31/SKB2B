#!/usr/bin/env bash

echo "Autofix ..."
echo ""

sudo docker exec -it m5_app /bin/bash
composer update
cd storage
chmod 777 logs 
cd framework
chmod 777 cache && chmod 777 sessions && chmod 777 views
cd ../../bootstrap
chmod 777 cache
cd ../
exit;

echo "Done with web"
echo ""
echo "ok..."