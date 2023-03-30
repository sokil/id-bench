bench:
	docker compose run --rm php ./bin/bench mysql insertAutoIncrement -i 5000 -b 2000

mongosh:
	docker compose run --rm mongodb_6_0 mongosh mongodb_6_0/bench