aws ec2 run-instances --image-id ami-d05e75b8 --count2 --instance-type t2.micro --key-name satyajit --security-group-ids sg-877562e0 --subnet-id subnet-5a391703 --associate-public-ip-address --user-data file://install-webserver.sh --debug