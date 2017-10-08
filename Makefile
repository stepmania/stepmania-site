SPECIAL_PLACE := ${shell pwd}
CONT_NAME := stepmania
HOST_PORT := 8080
SERV_PORT := 2808

all: start

build:
	docker build -t ${CONT_NAME} .

start:
	docker run -dti -p ${HOST_PORT}:${SERV_PORT} -v "${SPECIAL_PLACE}:/var/www" --name "${CONT_NAME}" ${CONT_NAME}

stop:
	docker stop /${CONT_NAME}
	docker rm /${CONT_NAME}

restart: stop start

.PHONY: all start stop restart
