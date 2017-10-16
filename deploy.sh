#!/bin/bash
# VM deployment script
#optimus-app00
var=$3;
$var/vmprovision.pl --operation clone --vmname $2 --vmname_destination $1 --datacenter 'Datacenter 02' --datastore local-esx006-01 --vmhost slkutcoresx006.sol.net --vmhost_destination slkutcoresx006.sol.net --server slkutvirtvcs02.sol.net --username root --password 1qaz2wsx@
$var/poweron.sh $1 $var
