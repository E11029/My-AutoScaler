#!/bin/bash
#Node_IP=`cat /public/newvmip.csv | grep 10.10| cut -d , -f 2 | tr -d  "\""`
#Node_IP=`cat /public/newvmip.csv |grep 10| awk '{print $2}'| sed "s/[^a-z.|0-9]//g;"`
#Node_IP=10.10.10.104
ssh -o StrictHostKeyChecking=no root@10.179.241.7 "/config/pool.sh $1 $2"
