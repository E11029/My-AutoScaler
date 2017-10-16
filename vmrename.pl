

#!/usr/bin/perl -w
use strict;
use warnings;
use VMware::VIRuntime;
use VMware::VILib;
# Get the required vmname and newvmname options
my %opts = (
vmname => {
type => "=s",
help => "Current name of virtual machine",
required => 1,
},
newvmname => {
type => "=s",
help => "New name of virtual machine",
required => 1,
},
);
Opts::add_options(%opts);
Opts::parse();
Opts::validate();

my $vm_name = Opts::get_option("vmname");
my $new_vm_name = Opts::get_option("newvmname");


#Connect to vSphere
Util::connect();

# Obtain inventory objects for the VM
my $entity_views = Vim::find_entity_views( view_type => 'VirtualMachine',
                                filter => { 'name' => $vm_name },

);
my $entity_views1 = Vim::find_entity_views( view_type => 'VirtualMachine',);

foreach my $entity_view (@$entity_views1) {
if($entity_view->name eq  $vm_name){

 
 printf "%s is going to shutdown\n",$entity_view->name;
$entity_view->PowerOffVM();

}


}
 printf "hii\n";
# Make sure we only have one entry returned
if (scalar @$entity_views == 1) {

# Seems strange to do a for loop for one entry, but this is how the example was set up
        foreach (@$entity_views) {
# Specify the new name

                my $vm_config_spec = VirtualMachineConfigSpec->new( name => $new_vm_name) ;
# And make the change
                $_->ReconfigVM_Task(spec=>$vm_config_spec);
        }
} else {
        printf "%d entries returned, cowardly refusing to do anything.\n",scalar @$entity_views;
        exit 1;
}

# Disconnect from the server
Util::disconnect();

