#!/bin/bash

docker-compose pull
docker-compose build
docker-compose down
docker-compose up -d
