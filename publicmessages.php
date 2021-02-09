<!DOCTYPE html>
<html>

<head>
	<title>InfoPoint API - Public Messages</title>
    <link rel="stylesheet" type="text/css" href="infopoint.css">
</head>

<body>
	<h1>InfoPoint API - Public Messages</h1>
    
    <p style="color:#F00; font-weight: 700; font-size: 1.5em;">*** This page does <em>not</em> refresh automatically ***</p>
	
	<div class="infopoint-wrapper">

	<?php
  
  // Eventually, this should come from a config.php file and/or the WordPress database
  // $infopoint_vars = include 'config.php';
  $file = file_get_contents('infopoint_config.json');
  $infopoint_vars = json_decode($file, true);

  $infopoint_url = $infopoint_vars['InfoPoint'];
  $get_current_messages = $infopoint_vars['GetCurrentMessages'];
  $no_messages = $infopoint_vars['NoMessages'];
  unset($infopoint_vars, $file);
  
  $ch = curl_init();
	curl_setopt($ch, CURLOPT_GET, true);
	curl_setopt($ch, CURLOPT_URL, "${infopoint_url}${get_current_messages}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:text/json)'));
	$result = curl_exec($ch);
  curl_close($ch);
  unset($ch);
	
    $obj = json_decode($result);
    if (empty($obj)) {
        $obj = array((object)$no_messages);
    }
    foreach ($obj as $msg) {
        $days_of_week = $msg->DaysOfWeek;
        $from_date = strtotime($msg->FromDate);
        $from_time = $msg->FromTime;
        $message = $msg->Message;
        $published = $msg->Published;
        $to_date = strtotime($msg->ToDate);
        $to_time = $msg->ToTime;
        $now = strtotime(date("Y-m-d")."T".date("H:i:s").".500Z");
        
        if ($to_date > $now && $now > $from_date) {
            echo "<div>$message</div>";
        }
        
    }
    
	?>
	</div> <!-- infopoint-wrapper -->

  <p style="color:#F00; font-weight: 700; font-size: 1.5em;">*** This page does <em>not</em> refresh automatically ***</p>

</body>

</html>
