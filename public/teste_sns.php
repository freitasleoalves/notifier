<?php
	require './vendor/autoload.php';

	$params = array(
	    'credentials' => array(
	        'key' => '-- Your IAM User Key --',
	        'secret' => '-- Your IAM User Key Secret --',
	    ),
	    'region' => 'us-east-1', // < your aws from SNS Topic region
	    'version' => 'latest'
	);
	$sns = new \Aws\Sns\SnsClient($params);
	
	$args = array(
	    "MessageAttributes" => [
	                // You can put your senderId here. but first you have to verify the senderid by customer support of AWS then you can use your senderId.
	                // If you don't have senderId then you can comment senderId 
	                // 'AWS.SNS.SMS.SenderID' => [
	                //     'DataType' => 'String',
	                //     'StringValue' => ''
	                // ],
	                'AWS.SNS.SMS.SMSType' => [
	                    'DataType' => 'String',
	                    'StringValue' => 'Transactional'
	                ]
	            ],
	    "Message" => "-- Message comes here --",
	    "PhoneNumber" => "-- +{CountryCode}{PhoneNumber} --"   // Provide phone number with country code
	);
	
	$result = $sns->publish($args);
	
	var_dump($result); // You can check the response