$ /bin/bash
aws sns create-topic --name satyamp

aws sns set-topic-attributes --topic-arn arn:aws:sns:us-east-1:979737851092:satyamp --attribute-name DisplayName --attribute-value satyamp

aws sns subscribe --topic-arn arn:aws:sns:us-east-1:979737851092:satyamp --protocol email --notification-endpoint satyajit.baral@gmail.com


