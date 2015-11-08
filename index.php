<?php
require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

$s3 = $sdk->createS3();
$result = $s3 ->listBuckets();
// Get the name of each bucket
$results = $result->search('Buckets[].Name');

print $results;
