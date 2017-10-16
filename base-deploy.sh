
#!bin/bash
x=$2'Events';
myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    create table $2(Servername varchar(20) NOT NULL,Deployed_Date DATETIME NOT NULL,monitor int(1) NOT NULL);
    create table $x(Events LONGTEXT ,Time datetime,Status varchar(5) );
QUERY_INPUT
        );
var=`pwd`
mkdir $var/$2;
cp -r $var/base-setup/* $var/$2;
a=0

while [ $a -lt $1 ]
do   
   a=`expr $a + 1`


NEW_NODE=$2$3$a
      $var/deploy.sh $NEW_NODE $4 $var
       myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    update cluster  set Num_servers='$a'  where clustername='$2'; 
    insert into $2(Servername,Deployed_Date,monitor) VALUES("$NEW_NODE",now(),1);
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
       $var/f5_add.sh $NODE_IP $NEW_NODE
 
done

$var/$2/http_check.sh $var/$2 $2 $var &
echo success;

