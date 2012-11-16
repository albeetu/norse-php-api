<?php

require("IPVIKING_API.php");
$request = new IPvikingRequest("http://api.ipviking.com/api/?apikey=6899ec1a65a9565b32f7b6d5848ed45914f590d4e122df6da602a573fbeb4a84&seal=med", "GET",$requestdata);
$request->setVerb("GET");
$request->setAcceptType('application/html');
$request->execute();
echo $request->getResponseBody();

?>