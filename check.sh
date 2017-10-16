

NEW_NODE='final11';
clustername='final1';
myvariable=$(mysql -u root -p'password' -s -N <<QUERY_INPUT
    use MY_AUTO_SCALER;
    select monitor from cluster where clustername='final1';
  insert into $clustername(Servername,Deployed_Date,monitor) VALUES("$NEW_NODE",now(),1);
QUERY_INPUT
)
echo $1 $2 $3;
var='1';
var=`echo $var|bc`
if [ $myvariable -eq $var ]
then
echo ok;
fi
