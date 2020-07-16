#!/usr/bin/env bash

set -e

cd wordpress

while :
do
  wp action-scheduler run
done

