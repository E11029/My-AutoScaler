
#!/usr/bin/perl
use strict;
use warnings;
use VMware::VIRuntime;
use VMware::VILib;
# Get the required vmname and new folder options
my %opts = (
vmname => {
type => "=s",
help => "Name of virtual machine",
required => 1,
},
folder => {
type => "=s",
help => "Name of folder to move VM into",
required => 1,
},
datacenter => {
type => "=s",
help => "Name of Datacenter",
required => 0,
},


);
Opts::add_options(%opts);
Opts::parse();
Opts::validate();

my $vm_name = Opts::get_option("vmname");
my $folder =Opts::get_option("folder");
my $vm_newname = Opts::get_option("newvmname");

#
## Connect to vSphere
Util::connect();
#
# Obtain inventory objects for the VM
my $vm_entities = Vim::find_entity_views( view_type => 'VirtualMachine',
                                filter => { 'name' => $vm_name },
                                );

                                # Obtain inventory objects for the folder
                                my $folder_entities;
                                if (defined(Opts::get_option("datacenter"))) {
                                        my $datacenter=Opts::get_option("datacenter");
                                        my $dc_mos = Vim::find_entity_views( view_type => 'Datacenter',
                                                                               filter => { 'name' => $datacenter },);
                                                                                        my $dc_mo=(@$dc_mos)[0];
                                                                                                $folder_entities=Vim::find_entity_views(view_type => 'Folder',
                                                                                                                                begin_entity => $dc_mo,
                                                                                                                                                                filter => { 'name' => $folder});
                                                                                                                                                                } else  {
                                                                                                                                                                       $folder_entities=Vim::find_entity_views(view_type => 'Folder',
                                                                                                                                                                                                        filter => { 'name' => $folder});
                                                                                                                                                                                                        }


                                                                                                                                                                                                        # Make sure we only have one entry returned for both VM & folder
                                                                                                                                                                                                        if ((scalar @$folder_entities ==1)&&(scalar @$vm_entities ==1)) {
#                                                                                                                                                                                                        # Get the first (and only) entry from the folder array
                                                                                                                          my $new_folder=(@$folder_entities)[0];
                                                                                                                                                                                                                # And move the VM
                                                                                                                                                                                                                       
$new_folder->MoveIntoFolder_Task(list=>$vm_entities);
                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                    				  printf "Ambiguous entries, %d VMs and %d folders. Cowardly refusing to do anything\n", scalar @$vm_entities, scalar @$folder_entities;
                                                                                                                                                                                                                                        exit 1;
                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                        # Disconnect from the server
                                                                                                                                                                                                                                        Util::disconnect();

