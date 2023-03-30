bench: bench-mysql bench-postgres bench-mongodb

bench-mysql:
	docker compose run --rm php ./bin/bench mysql insertAutoIncrement -i 5000 -b 2000 -vvv
	docker compose run --rm php ./bin/bench mysql insertUuidv1 -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench mysql insertUuidv4 -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench mysql insertUuidv5 -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench mysql insertUuidv7 -b 2000 -i 5000 -vvv


bench-postgres:
	docker compose run --rm php ./bin/bench postgres insertAutoIncrement -i 5000 -b 2000 -vvv
	docker compose run --rm php ./bin/bench postgres insertUuidv1 -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench postgres insertUuidv4 -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench postgres insertUuidv5 -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench postgres insertUuidv7 -b 2000 -i 5000 -vvv

bench-mongodb:
	docker compose run --rm php ./bin/bench mongodb insertAutoIncrement -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench mongodb insertUuidv1 -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench mongodb insertUuidv4 -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench mongodb insertUuidv5 -b 2000 -i 5000 -vvv
	docker compose run --rm php ./bin/bench mongodb insertUuidv7 -b 2000 -i 5000 -vvv

mongosh:
	docker compose run --rm mongodb_6_0 mongosh mongodb_6_0/bench