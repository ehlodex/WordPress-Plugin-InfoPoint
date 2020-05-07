<!DOCTYPE html>
<html>

<head>
	<title>InfoPoint API Test v2</title>
    <link rel="stylesheet" type="text/css" href="infopoint.css">
    <script src="infopoint.js"></script>
</head>

<body>
	<h1>InfoPoint API Test v. 2.20.05.07-1130</h1>
    
    <p style="color:#F00; font-weight: 700; font-size: 1.5em;">*** This page does <em>not</em> refresh automatically ***</p>
	
	<div class="infopoint-wrapper">

	<?php

  $file = file_get_contents('infopoint_config.json');
  $infopoint_vars = json_decode($file, true);

	$infopoint_url = $infopoint_vars['InfoPoint'];
  $get_visible_routes = $infopoint_vars['GetVisibleRoutes'];
  $no_vehicle = $infopoint_vars['NoVehicle'];
  unset($infopoint_vars, $file);
  
  $ch = curl_init();
	curl_setopt($ch, CURLOPT_GET, true);
	curl_setopt($ch, CURLOPT_URL, "${infopoint_url}${get_visible_routes}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:text/json)'));
	$result = curl_exec($ch);
	curl_close($ch);
	
    $obj = json_decode($result);
    foreach ($obj as $route) {
		$RouteId = $route->RouteId;
		$LongName = $route->LongName;
		$Vehicles = $route->Vehicles;
		$Color = $route->Color;
		$TextColor = $route->TextColor;
		
		if ($RouteId > 99) continue;
		
        $AvailShowEmpty = True;
        
        if ($Vehicles == [] && $AvailShowEmpty) {
            array_push($Vehicles, (object)$no_vehicle);
        }
        
		foreach($Vehicles as $vehicle) {
            $Direction = $vehicle->Direction;
            $Status = $vehicle->DisplayStatus;
            echo "<div class='infopoint-route infopoint-route-${RouteId}' style='background:#${Color}'>";
            echo "<div class='infopoint-route-data infopoint-route-number'>${RouteId}</div>";
            echo "<div class='infopoint-route-data infopoint-route-name'>${LongName} (${Direction})</div>";
            echo "<div class='infopoint-route-data infopoint-route-status'>${Status}</div>";
            echo "</div>";
        }
    }
    
	?>
	</div> <!-- infopoint-wrapper -->

  <p style="color:#F00; font-weight: 700; font-size: 1.5em;">*** This page does <em>not</em> refresh automatically ***</p>

</body>

</html>
