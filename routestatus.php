<!DOCTYPE html>
<html>

<head>
	<title>InfoPoint API - Route Status</title>
    <link rel="stylesheet" type="text/css" href="infopoint.css">
    <script src="infopoint.js"></script>
</head>

<body>
	<h1>InfoPoint API - Route Status</h1>
    
    <p style="color:#F00; font-weight: 700; font-size: 1.5em;">*** This page does <em>not</em> refresh automatically ***</p>
	
	<div class="infopoint-wrapper">

	<?php
  
  // Eventually, this should come from a config.php file and/or the WordPress database
  // $infopoint_vars = include 'config.php';
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
		$route_id = $route->RouteId;
		$route_name = $route->LongName;
		$vehicles = $route->Vehicles;
		$bg_color = $route->Color;
    $fg_Color = $route->TextColor;
    $vehicle_count = count($vehicles);
		
		if ($route_id > 99) continue;
		
        $infopoint_show_empty = True;
        
        if ($vehicles == [] && $infopoint_show_empty) {
            array_push($vehicles, (object)$no_vehicle);
        }
    
    echo "<div class='infopoint-route-group infopoint-route-${route_id}' style='background:#${bg_color}'>";
		foreach($vehicles as $vehicle) {
            $direction = $vehicle->Direction;
            $status = $vehicle->DisplayStatus;
            echo "<div class='infopoint-route-block'>";
            echo "<div class='infopoint-route-data infopoint-route-number'>${route_id}</div>";
            echo "<div class='infopoint-route-data infopoint-route-name'>${route_name} (${direction})</div>";
            echo "<div class='infopoint-route-data infopoint-route-status'>${status} (${vehicle_count})</div>";
            echo "</div>";
        }
    echo "</div> <!-- infopoint-route-group -->";
    }
    
	?>
	</div> <!-- infopoint-wrapper -->

  <p style="color:#F00; font-weight: 700; font-size: 1.5em;">*** This page does <em>not</em> refresh automatically ***</p>

</body>

</html>
