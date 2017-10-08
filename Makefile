SPECIAL_PLACE := ${shell pwd}
PORT := 2808

all: start

build:
	docker build -t stepmania .

start:
	docker run -dti -p ${PORT}:${PORT} -v "${SPECIAL_PLACE}:/var/www" --name "stepmania" stepmania

stop:
	docker stop /stepmania
	docker rm /stepmania

restart: stop start

.PHONY: all start stop restart
