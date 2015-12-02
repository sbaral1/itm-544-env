
<?php

require 'vendor/autoload.php';
use Aws\Common\Aws;
use Aws\SimpleDb\SimpleDbClient;
use Aws\S3\S3Client;
use Aws\Sns\SnsClient;
use Aws\Sqs\sqsclient;
use Aws\Sns\Exception\InvalidParameterException;
$aws = Aws::factory('./vendor/aws/aws-sdk-php/src/Aws/Common/Resources/custom-config.php');
$client = $aws->get('S3'); 
$sdbclient = $aws->get('SimpleDb'); 
$snsclient = $aws->get('Sns'); 
$sqsclient = $aws->get('Sqs');
$NAME = file_get_contents("name.txt");
$NAME_SDB = str_replace("-", "", $NAME)."sdb";
$UUID = uniqid();
$email = str_replace("@","-",$_POST["email"]); 
$bucket = str_replace("@","-",$_POST["email"]).time();
$bucket = str_replace(" ","","$NAME-s3-$bucket"); 
$phone = $_POST["phone"];
$topic = "$NAME-sns";
$itemName = 'images-'.$UUID;

$result = $client->createBucket(array(
    'Bucket' => $bucket
));
$client->waitUntil('BucketExists', array('Bucket' => $bucket));
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['uploaded_file']['name']);

if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $uploadfile)) {

} else {
    echo "Possible file upload attack!\n";
}
$pathToFile = $uploaddir.$_FILES['uploaded_file']['name'];

$result = $client->putObject(array(
    'ACL'        => 'public-read',
    'Bucket'     => $bucket,
    'Key'        => $_FILES['uploaded_file']['name'],
    'SourceFile' => $pathToFile,
    'Metadata'   => array(
        'timestamp' => time(),
        'md5' =>  md5_file($pathToFile),
    )
));

$url= $result['ObjectURL'];
$result = $sdbclient->createDomain(array(
    'DomainName' => "$NAME_SDB", 
));
$result = $sdbclient->putAttributes(array(
    'DomainName' => "$NAME_SDB",
    'ItemName' =>$itemName ,
    'Attributes' => array(
        array(
           'Name' => 'rawurl',
            'Value' => $url,
        ),
        array(
           'Name' => 'bucket',
            'Value' => $bucket,
        ),
        array(
           'Name' => 'id',
           'Value' => $UUID,
            ),  
        array(
            'Name' =>  'email',
            'Value' => $_POST['email'],
         ),
        array(
            'Name' => 'phone',
            'Value' => $phone,
        ),
         array(
            'Name' => 'finishedurl',
            'Value' => '',
        ),  

        array(
            'Name' => 'receiptHandle',
            'Value' => '',
        ),      
         array(
            'Name' => 'filename',
            'Value' => basename($_FILES['uploaded_file']['name']),
        ), 
    ),
));

$sqs_queue_url = $sqsclient->getQueueUrl(array(
    'QueueName' => "$NAME-sqs",
));
//var_export($sqs_queue_url->getkeys());
$sqs_queue_url = $sqs_queue_url['QueueUrl'];
# Send the message
$result = $sqsclient->sendMessage(array(
    'QueueUrl' => $sqs_queue_url,
    'MessageBody' => $UUID,
    'DelaySeconds' => 15,
));
$result = $snsclient->listTopics();
$topicArn="";
foreach ($result->getPath('Topics/*/TopicArn') as $topicArnTmp) {
    if ( strstr($topicArnTmp,$topic) ) {
        $topicArn=$topicArnTmp;
    }
}
try {
$result = $snsclient->subscribe(array(
    'TopicArn' => $topicArn,
    'Protocol' => 'sms',
    'Endpoint' => $phone,
)); } catch(InvalidParameterException $i) {

}
try{
$result = $snsclient->subscribe(array(
    'TopicArn' => $topicArn,
    'Protocol' => 'email',
    'Endpoint' => $_POST["email"],
)); } catch(InvalidParameterException $i) {

} 
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css"/>
    <title>Process.php</title>
</head>

<body>
    <div id="main">
        <header>
            <h1>Picture Uploader</h1>

            <p>A mini project for ITMO 544 - Cloud Computing</p>
            <p>Illinois Institute of Technology</p>
            <p><a href="https://github.com/gpuenteallott/itmo544-CloudComputing-mp1">Project in GitHub</a></p>
        </header>

        <h2>Process step</h2>
        <p>What have you done?!</p>
        <ol>
            <li>Picture uploaded to S3 bucket <? echo $bucket ?></li>
            <li>Information recorded in SimpleDB</li>
            <li>SQS message added to recover the information in the following step</li>
            <li>Phone number <? echo $phone ?> subscribed</li>
            <li>Email <? echo $_POST["email"] ?> suscribed</li>
        </ol>

        <p class="next">Continue to next step --> <a href="resize.php">Stamp</a></p>
    </div>
</body>
</html>
