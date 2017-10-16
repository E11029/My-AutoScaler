

#for i in optimus-app0{1..2}
while [ 1 ]
do
> alert.out


var=$1;
clustername=$2;
filepath=$3;
servercount=0;
myvariable=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    select monitor from cluster where clustername='$clustername';
QUERY_INPUT
)
if [ $myvariable -eq 1 ]
then
Totalcpu=0;
Totalmem=0;
myvariable=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    select servername from $clustername where monitor=1;
QUERY_INPUT
)
servercount=`echo $myvariable | wc -w`
#echo $myvariable;

  for i in $myvariable
  do
var2=`ssh -o StrictHostKeyChecking=no $i "/bin/bash /root/optimus/check.sh -p httpd -w 50 -c 70 -m 30 -n 40"`; 
echo $var2 >> $var/alert.out 
cpu=`echo $var2 | cut -d " " -f 4| cut -d "%" -f 1`;
mem=`echo $var2 | cut -d " " -f 6| cut -d "%" -f 1`;
cpu=${cpu/\.*};
mem=${mem/\.*};
myvariable=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    INSERT INTO  $i(Time,cpu,Mem) VALUES(now(),'$cpu','$mem');
QUERY_INPUT
)
Totalcpu=`expr $Totalcpu + $cpu`;
Totalmem=`expr $Totalmem + $mem`;
done

Totalmem=`expr $Totalmem / $servercount`;
  
#echo $Totalmem; 
$var/threshold.sh  $Totalmem $var $clustername $filepath; 
fi

sleep 30
done
