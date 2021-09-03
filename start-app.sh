#!/bin/bash
cd $(dirname $0)
docker-compose down
docker-compose up -d
while true; do
	sleep 3
	sudo docker exec -i mariadb mysql -umfgtest -p'Qty[U0Fy;hff9g3&~~Xb8bbqD]cMGM>x' < site/xes.sql && break
done
