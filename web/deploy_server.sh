
#!bin/bash

myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    create table $2(Servername varchar(20) NOT NULL,Deployed_Date DATETIME NOT NULL,monitor int(1) NOT NULL);
QUERY_INPUT
        );
mkdir /var/www/html/MY_AUTO_SCALER/$2;
cp -r /var/www/html/MY_AUTO_SCALER/base-setup/* /var/www/html/MY_AUTO_SCALER/$2;
a=0

while [ $a -lt $1 ]
do   
   a=`expr $a + 1`


NEW_NODE=$2$3$a
      /var/www/html/MY_AUTO_SCALER/$2/deploy.sh $NEW_NODE $4 /var/www/html/MY_AUTO_SCALER/$2
       myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    update cluster  set Num_servers='$a'  where clustername='$2';
    insert into $clustername(Servername,Deployed_Date,monitor) VALUES("$NEW_NODE",now(),1);
    create table $NEW_NODE(Time DATETIME NOT NULL,cpu  INT(3) NOT NULL,Mem  INT(3) NOT NULL);
QUERY_INPUT
)
      sleep 60
      echo go_now
      #ssh -o StrictHostKeyChecking=no optimus-app00 "echo 'HOSTNAME=$NEW_NODE > /etc/sysconfig/network'; reboot";
      ssh -o StrictHostKeyChecking=no optimus-new.optimus.com << EOSSH
      echo $NEW_NODE > /var/www/html/index.html;
      echo HOSTNAME=$NEW_NODE > /etc/sysconfig/network; reboot;
EOSSH
      sleep 70
      NODE_IP=`nslookup $NEW_NODE | tail -2 | head -1 | awk '{print $2}'`
      sleep 5
       /var/www/html/MY_AUTO_SCALER/$2/f5_add.sh $NODE_IP $NEW_NODE
 
done

/var/www/html/MY_AUTO_SCALER/$2/http_check.sh /var/www/html/MY_AUTO_SCALER/$2 &
echo success;

