<table width="900" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td><h2>Submission API Example</h2></td>
  </tr>
  <tr>
    <td>
	<?php 
	
	require("IPVIKING_API.php");
	// show what we are submitting
	echo "Looking up 208.74.76.10<br><br>";
	
	$ips=array();
	$ips[] = "208.74.76.10";
	
	$good=1;
	$ips = array_unique($ips);
	if(count($ips)==0) { $good=0; }	
	foreach($ips as $dip) 
	{
		// we set the data to supply the resquest 
		$requestdata = array('apikey' => '6899ec1a65a9565b32f7b6d5848ed45914f590d4e122df6da602a573fbeb4a84',
					 'method' => 'submission',
					 'ip' => $dip,
					 'category' => '5',
					 'protocol' => '40',
					 'timestamp' => time() );
		$request = new IPvikingRequest("http://api.ipviking.com/api/", "PUT",$requestdata);
		// set the request method  
		// GET POST PUT or DELETE 
		$request->setVerb("PUT");
		$request->setMethod('submission');
		// pick either json, xml or html for seal 
		$request->setAcceptType('application/json');
		// now execute 
		$request->execute();
		// below a user can decide how to use the response 
		// we are showing an example here 
		// this collects the response header information 
		$ipviking_info = $request->getResponseInfo();
		$ipv_status = $ipviking_info['http_code'];
		switch($ipv_status) {
			case 201:
				// submission was already processed and accepted
				$http_code = $request->getStatusCodeMessage($ipv_status);
				echo "<b>IPviking Response:</b> ".$request->getMethod()." of $dip response <b>($ipv_status) $http_code</b> created and accepted<br>\n";				
				break;
			case 202:
				// submission accepted but in processing queue
				$http_code = $request->getStatusCodeMessage($ipv_status);
				echo "<b>IPviking Response:</b> ".$request->getMethod()." of $dip response <b>($ipv_status) $http_code</b> Aceppted in processing queue<br>\n";				
				break;
			case 409:
				// conflict record already exists, either submitted by
				// someone else or already submitted once
				$http_code = $request->getStatusCodeMessage($ipv_status);
				echo "<b>IPviking Response:</b> ".$request->getMethod()." of $dip response <b>($ipv_status) $http_code</b> Already submitted record exists<br>\n";				
				break;
			default:
				// here we should raize an error to admins 
				$http_code = $request->getStatusCodeMessage($ipv_status);
				echo "<b>IPviking Response:</b> ".$request->getMethod()." of $dip response <b>($ipv_status) $http_code</b> <br>\n";				
		}
	}
?>
&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>