<table width="900" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td><h2>Blacklist API Example</h2></td>
  </tr>
  <tr>
    <td>
<?php 
	require("IPVIKING_API.php");
	$email = "alberttu@yahoo.c</td>dom";
	echo "Looking up ".$_SERVER['REMOTE_ADDR']." (You)<br>";
	echo "Looking up all MX records for $email<br><br>";
	
	$good=1;
	$ips=array();	
	$ips[] = $_SERVER['REMOTE_ADDR'];	
	
	$atIndex = strrpos($email, "@");
	$domain = substr($email, $atIndex+1);	
	$dnsr = dns_get_record($domain,DNS_MX);
	foreach($dnsr as $key => $val) { 
		$ips[] = gethostbyname($val['target']); 
		}
	$ips = array_unique($ips);
	if(count($ips)==0) { $good=0; }	
	$whitelist=0; $blacklist=0;
	$total = count($ips);
	foreach($ips as $dip) 
	{
		// we set the data to supply the resquest 
		$requestdata = array('apikey' => '6899ec1a65a9565b32f7b6d5848ed45914f590d4e122df6da602a573fbeb4a84',
					 'method' => 'blacklist',
					 'ip' => $dip,
					 'categories' => '',
					 'options' => 'url_detail');
		$request = new IPvikingRequest("http://api.ipviking.com/api/", "POST",$requestdata);
		// set the request method  
		// GET POST PUT or DELETE 
		$request->setVerb("POST");
		// pick either json, xml or html for seal 
		$request->setAcceptType('application/json');
		// generate details URL for user output 
		$request->Seturldisplay();
		// now execute 
		$request->execute();
		// below a user can decide how to use the response 
		// we are showing an example here 
		// this collects the response header information 
		$ipviking_info = $request->getResponseInfo();
		$ipv_status = $ipviking_info['http_code'];
		switch($ipv_status) {
			case 204:
				$request->ipvikingDisplayStatus($ipv_status,$dip,'blacklist');
				$whitelist++;
				break;
			case 302:
				$request->ipvikingDisplayStatus($ipv_status,$dip,'blacklist');
				$request->ipvikingDisplayReasons('html','blacklist');
				$good=0;
				$blacklist++;
				break;
			default:
				// here we should raize an error to admins 
				$request->ipvikingDisplayStatus($ipv_status,$dip,'blacklist');
				//break 2;
		}
		$IPvikingDisplay .= $request->IPvikingShowCheck();
		$IPvikingDisplay .= $request->IPvikingShowReasons();			
	}
	// if for example more then 50% of IP's failed for this 1 transaction deny
	// if not approve 
	$percent_blacklisted = round(($blacklist*100)/$total);
	if($percent_blacklisted < 50) {
		// threashold 50%, this will stop only the really BAD boys
		$good=1;
	}
	if($good || $error=0) 
	{
		// results from IPViking was positive do something
		echo $IPvikingDisplay." ".$percent_blacklisted."% Blacklisted acceptable for contact form";
	} else {
		$IPvikingDisplay .= "<b>Error</b> data not submitted for the following reasons $percent_blacklisted% blacklisted: <br>";
		echo $IPvikingDisplay;
	}

?>
</td>
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