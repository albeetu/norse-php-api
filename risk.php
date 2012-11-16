<table width="900" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td><h2>Risk Assessment API Example</h2></td>
  </tr>
  <tr>
    <td>
	<?php 
	
	require("IPVIKING_API.php");
	
	$ips=array("208.74.76.5","208.74.76.4","208.74.76.1","208.74.76.2","208.74.76.3","208.74.76.6");
	
	$good=1;
	$ips = array_unique($ips);
	if(count($ips)==0) { $good=0; }	
	$whitelist=0; $blacklist=0;
	$total = count($ips);
	foreach($ips as $dip) 
	{
		// we set the data to supply the resquest 
		$requestdata = array('apikey' => '6899ec1a65a9565b32f7b6d5848ed45914f590d4e122df6da602a573fbeb4a84',
					 'method' => 'risk',
					 'ip' => $dip,
					 'categories' => '',
					 'options' => 'url_detail');
		$request = new IPvikingRequest("http://api.ipviking.com/api/", "POST",$requestdata);
		// set the request method  
		// GET POST PUT or DELETE 
		  $request->setVerb("POST");
		  $request->setMethod('risk');
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
				$request->ipvikingDisplayStatus($ipv_status,$dip,$request->getMethod());
				$whitelist++;
				break;
			case 302:
				$request->ipvikingDisplayStatus($ipv_status,$dip,$request->getMethod());
				$request->ipvikingDisplayReasons('html',$request->getMethod());
				$good=0;
				$blacklist++;
				break;
			default:
				// here we should raize an error to admins 
				$request->ipvikingDisplayStatus($ipv_status,$dip,$request->getMethod());
				//break 2;
		}
		$IPvikingDisplay .= $request->IPvikingShowCheck();
		$IPvikingDisplay .= $request->IPvikingShowReasons();			
	}
	// decide what score threshold to set
	// or use the reason details to make determination
	// this would be a good idea to build a local table with score
	// decisions and thresholds 
	if($request->GetRisk() <= 35) 
	{
		// results from IPViking was positive do something
		echo $IPvikingDisplay." Risk is good enough ".$request->GetRisk()."% (we require less then 35% risk)";
	} else {
		$IPvikingDisplay .= "<b>Declined</b> Risk is to high ".$request->GetRisk()."% Above 35%<br>";
		echo $IPvikingDisplay;
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
