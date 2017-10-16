#!/bin/bash

while [ 1 ]
do
Time="";
Time=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    select MAX(time) from queue;
QUERY_INPUT
        );


if [ "$Time" != "NULL" ] 
  then
details=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    select details  from queue where time='$Time';
QUERY_INPUT
        );

scriptcount=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    select scriptcount  from queue where time='$Time';
   delete from queue where time='$Time';
QUERY_INPUT
        );
if [ $scriptcount -eq 1 ]

 then
./base-deploy.sh $details &
exit 0;
fi

sleep 2;

else
sleep 5;

fi





done
