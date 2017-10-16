#!/bin/bash
#
#script to check if the threshold is critical
#
#WARN=`cat /root/alert.out | grep -i "WARN" | uniq -c | awk 'print $1'`
#CRIT=`cat /root/alert.out | grep -i "CRIT" | uniq -c | awk 'print $1'`

SOURCE=$1
myname=$( basename $0 )
PID=$$
DATE=$( date +%Y%m%d )
ProcessCount=$( ps -ef | grep "bash ${0}[ ]*${SOURCE}$" | grep -v grep | grep -v $PID | wc -l )
if [[ ${ProcessCount} -ge 1 ]]
then
   echo "$(date) ( ${PID} ) - Aleady Running"
   exit 0
fi
vari=$1
serverpath=$2
clustername=$3;
filepath=$4;
myvariable=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    select cpu_W,cpu_C,cpu_F,Mem_W,Mem_C,Mem_F,count,template_name,Num_servers_base,Num_servers,max_servers,finecount,sub_name  from cluster  where clustername='$3';
QUERY_INPUT
)

crit=`echo $myvariable | cut -d " " -f 4`;
warn=`echo $myvariable | cut -d " " -f 5`;
fine=`echo $myvariable | cut -d " " -f 6`;
count=`echo $myvariable | cut -d " " -f 7`;
finecount=`echo $myvariable | cut -d " " -f 12`;
servercount=`echo $myvariable | cut -d " " -f 10`;
maxcount=`echo $myvariable | cut -d " " -f 11`;
template=`echo $myvariable | cut -d " " -f 8`;
basecount=`echo $myvariable | cut -d " " -f 9`;
subname=`echo $myvariable | cut -d " " -f 13`;
#==============================================================================================================================#
#echo $crit $warn $fine $count $finecount $servercount $maxcount $template $basecount $vari $serverpath $clustername;					#get average threshold #
add=0
#sleep 90
#==============================================================================================================================#

# get critical state count
#have to update
event="";
Status="";
if [ $vari -ge $crit ] 
then 
   count=`expr $count + 1`
   event="critical value="$vari;
   Status="cric" 
   echo $count
   if [ $count -eq 5 ]
   then
      echo start_if;
        event=$event"deployment";
     myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    update cluster  set count=0  where clustername='$clustername';
    update cluster  set finecount=0  where clustername='$clustername';
QUERY_INPUT
)
   if [  $servercount -ne $maxcount ]
     then   
      servercount=`expr $servercount + 1`;
      NEW_NODE=$clustername$subname$servercount;
      $filepath/deploy.sh $NEW_NODE $template $filepath
       myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    update cluster  set Num_servers='$servercount'  where clustername='$clustername';
    insert into $clustername(Servername,Deployed_Date,monitor) VALUES("$NEW_NODE",now(),1);
    create table $NEW_NODE(Time DATETIME NOT NULL,cpu  INT(3) NOT NULL,Mem  INT(3) NOT NULL);
QUERY_INPUT
)
      event=$event$myvariable2
#      echo hello
      echo "Waiting 60 seconds for everything to get stabilized"
      sleep 60
#      echo go_now
      #ssh -o StrictHostKeyChecking=no optimus-app00 "echo 'HOSTNAME=$NEW_NODE > /etc/sysconfig/network'; reboot"; 
      ssh -o StrictHostKeyChecking=no optimus-new.optimus.com << EOSSH
      echo $NEW_NODE > /var/www/html/index.html;
      echo HOSTNAME=$NEW_NODE > /etc/sysconfig/network; reboot;
EOSSH
      sleep 70
      NODE_IP=`nslookup $NEW_NODE | tail -2 | head -1 | awk '{print $2}'`
      sleep 5
      $filepath/f5_add.sh $NODE_IP $NEW_NODE
      exit 0	
   else
      event=$event"  cluster reach the max count"
      Status="cric";
 exit 0;

   fi
else
 myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
       update cluster  set count='$count'  where clustername='$clustername';
      update cluster  set finecount=0  where clustername='$clustername';
QUERY_INPUT
)
 
  fi


        #/usr/local/bin/python2.7 /root/optimus/deploy.py    

elif [ $vari -ge $warn  ]
then
  event=`echo warning_alert`;
   
elif [ $vari -le $fine ]
then 
   echo fine_stat
    finecount=`expr $finecount + 1`;
  echo $finecount
 event="cluster is healthy";
  Status="fine";
   if [ $finecount -eq 10 -a  $count -le 4 -a  $servercount -gt $basecount ]
   then
      echo "deregister from F5"
      count=0;
      finecount=0;
      NODE=$clustername$subname$servercount;
      servercount=`expr $servercount - 1`;
     echo $NODE $servercount;
    myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
   	 use MY_AUTO_SCALER;
   	 update cluster  set count='$count'  where clustername='$clustername';
   	 update cluster  set finecount=0  where clustername='$clustername';
   	 update cluster  set Num_servers='$servercount' where  clustername='$clustername';
   	 drop table $NODE;
   	 delete from $clustername where Servername='$NODE';
QUERY_INPUT
    ); 
  event=$NODE"is removed from pool and power off"
  Status="fine";
      NODE_IP=`nslookup $NODE | tail -2 | head -1 | awk '{print $2}'`
      sleep 5
      ssh -o StrictHostKeyChecking=no root@10.179.241.7 "/config/deregis.sh $NODE_IP $NODE" << END
END
      sleep 5 
      echo "power off"
      $filepath/poweroff.sh $NODE $filepath
	elif [ $finecount -lt 10 ] 
        then
       echo  'hiii';
   echo $finecount;
     myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    update cluster  set finecount='$finecount'  where clustername='$clustername';
QUERY_INPUT
	);
 
  	fi
else 
event=`echo 'normal'`; 
Status="fine";    
   
fi
table=$clustername"Events";
if [ "$event" != "" ] 
then
 myvariable2=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    insert into  $table(Events,Time,Status) VALUES('$event',now(),'$Status');
QUERY_INPUT
        );
fi
