aws autoscaling create-auto-scaling-group --auto-scaling-group-name satyajit_group --launch-configuration-name satyajit_config--load-balancer-names $2  --health-check-type ELB --min-size 3 --max-size 6 --desired-capacity 3 --default-cooldown 600 --health-check-grace-period 120 --vpc-zone-identifier subnet-09fdaa22 