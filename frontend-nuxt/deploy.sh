#!/bin/sh

npm run build
docker build -t admin_site .
