<?php 
include 'vendor/autoload.php';
include 'reviews.php';
include 'review.php';
include 'GPBMetadata\Reviews.php';

$from = new Review();
$from = new Reviews();


$api_key = "Anonymized";
$placeId = "ChIJ0YFrX5weEEgRPrPQrD230oQ";

$url = "https://places.googleapis.com/v1/places/$placeId";

$options = [
    "http" => [
        "method" => "GET",
        "header" => [
            "Content-Type: application/json",
            "X-Goog-Api-Key: $api_key",
            "X-Goog-FieldMask: displayName,rating,reviews"
        ]
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$response = array_slice(json_decode($response, true), 2);
$reviewsList = new Reviews();
$array = array();
$connect = mysqli_connect('localhost', 'root', 'Anonymized', 'testing');


foreach ($response['reviews'] as $key => $value) {
    $from = new Review();
    $from->setName($value['name']);
    $from->setRating($value['rating']);
    $from->setRelativePublishTimeDescription($value['relativePublishTimeDescription']);
    $from->setText($value['text']['text']);
    $from->setPublishTime($value['publishTime']);

    $sql = "INSERT INTO `Review` VALUES (\"" . $from->getName() . "\", \"" . $from->getRelativePublishTimeDescription() . "\", " . $from->getRating() . ", \"" . $from->getText() . "\", \"" . $from->getPublishTime() . "\")";
    
    $connect->query($sql);

    array_push($array, $from);
}

$reviewsList->setReviews($array);



//$fp = fopen('googleData.txt', 'w');
//fwrite($fp, $serializedData);
//fclose($fp);
?>
