

#!/usr/bin/perl
use strict;
use warnings;
use VMware::VIRuntime;

# Get the required vmname and new folder options
my %opts = (
vmname => {
type => "=s",
help => "Name of virtual machine",
required => 1,
},
deleteafter => {
type => "=i",
help => "Timestamp (in seconds afer epoch) for 'delete.after' setting",
required => 1,
},
);
Opts::add_options(%opts);
Opts::parse();
Opts::validate();

my $vm_name = Opts::get_option("vmname");
my $delete_after = Opts::get_option("deleteafter");

# Connect to vSphere
Util::connect();

# Obtain inventory objects for the VM
my $vm_entities = Vim::find_entity_views( view_type => 'VirtualMachine',
                                filter => { 'name' => $vm_name },
                                );

                                if ($delete_after < 1388556000) {
                                        print "Unreasonable date, dates should be after Jan 1, 2014\n";
                                                exit 1;
                                                }

                                                # Make sure we only have one entry returned for VM
                                                if (scalar @$vm_entities ==1) {
                                                # Get the first (and only) entry from the VM array
                                                        my $vm=(@$vm_entities)[0];
                                                        # And move the VM
                                                                my $option = OptionValue->new(key => 'delete.after', value => $delete_after);
                                                                        my $vmSpec = VirtualMachineConfigSpec->new(extraConfig => [$option]);
                                                                                $vm->ReconfigVM_Task(spec => $vmSpec);
                                                                                } else {
                                                                                        printf "Ambiguous entries, %d VMs. Cowardly refusing to do anything\n", scalar @$vm_entities;
                                                                                                exit 1;
                                                                                                }

                                                                                                # Disconnect from the server
                                                                                                Util::disconnect();
