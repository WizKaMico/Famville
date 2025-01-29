<?php

$endpoint = "http://192.168.1.10:8082";
$authorizationKey = "cb49eeeb-8f0b-4d18-bb5a-43c2efab7249";


$data = [
    "to" => $to,
    "message" => $message
];

// Initialize cURL session
$ch = curl_init();

// Configure cURL options
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",      
    "Authorization: $authorizationKey"      
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);           

// Execute the request and capture the response
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    // Print the response
    echo "Response from server: " . $response;
}

// Close the cURL session
curl_close($ch);
?>
