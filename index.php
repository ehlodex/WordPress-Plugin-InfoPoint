<!DOCTYPE html>
<html>

<head>
	<title>InfoPoint API Test 0.2</title>
    <link rel="stylesheet" type="text/css" href="infopoint.css">
    <script src="infopoint.js"></script>
</head>

<body>
	<h1>InfoPoint API Test v. 0.2.008.20200507</h1>
    
    <p style="color:#F00; font-weight: 700; font-size: 1.5em;">*** This page does <em>not</em> refresh automatically ***</p>
	
	<div class="infopoint-wrapper">

	<?php
  
  // Eventually, this should come from a proper config file and/or the WordPress database
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
  unset($ch);
	
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
    
    echo "<div class='infopoint-route-group infopoint-route-${RouteId}' style='background:#${Color}'>";
		foreach($Vehicles as $vehicle) {
            $Direction = $vehicle->Direction;
            $Status = $vehicle->DisplayStatus;
            echo "<div class='infopoint-route-block'>";
            echo "<div class='infopoint-route-data infopoint-route-number'>${RouteId}</div>";
            echo "<div class='infopoint-route-data infopoint-route-name'>${LongName} (${Direction})</div>";
            echo "<div class='infopoint-route-data infopoint-route-status'>${Status}</div>";
            echo "</div>";
        }
    echo "</div> <!-- infopoint-route-group -->";
    }
    
	?>
	</div> <!-- infopoint-wrapper -->

  <p style="color:#F00; font-weight: 700; font-size: 1.5em;">*** This page does <em>not</em> refresh automatically ***</p>

</body>

</html>
