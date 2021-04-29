<?php
   
   ini_set("allow_url_fopen", 1);
 
   $error = "\t";  
   $myCity = "";
   $myState = "";
   $myLat = "";
   $myLon = "";
   $myTemp = "";
   $myOvercast = "";
   $myHumidty = "";
   $myFeels = "";
   $myPopulation = "";
   
   
   if($_SERVER["REQUEST_METHOD"] == "POST") 
   {
      
	  $myCity = $_POST['city'];
	  $myState = $_POST['state'];

	  $coords = file_get_contents("http://geocode.xyz/$myCity,$myState?json=1");
	  $coordObj = json_decode($coords,true);
	  	  
	  $myLat = $coordObj['latt'];
	  $myLon = $coordObj['longt'];
	  	  
	  $weather = file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=$myLat&lon=$myLon&appid=106125ab823152affd98ead442af6e00&units=imperial");
	  $weatherObj = json_decode($weather,true);
	  
	  $myTemp = $weatherObj['main']['temp'];
	  $myFeels = $weatherObj['main']['feels_like'];
	  $myHumidty = $weatherObj['main']['humidity'];	  
	
      $nameID = file_get_contents("http://api.teleport.org/api/locations/$myLat%2C$myLon/");
	  $nameIDObj = json_decode($nameID,true);
	  	  
	  $myNameID = $nameIDObj['_embedded']["location:nearest-cities"][0]["_links"]["location:nearest-city"]["href"];
	  
	  $cityInfo = file_get_contents("$myNameID");
	  $cityInfoObj = json_decode($cityInfo,true);

	  $myPopulation = number_format($cityInfoObj["population"]);
	
   }
?>
<html>

	<head>
	
    	<title>myCity Info</title>
		
		<script src="./citysdk.js"></script>

		<style type = "text/css">
        	body 
         	{
            	font-family:Arial, Helvetica, sans-serif;
            	font-size:14px;
         	}
         
         	label 
         	{
            	font-weight:bold;
            	width:100px;
            	font-size:14px;
         	}
         
         	.box 
         	{
            	border:#666666 solid 1px;
         	}

      		.auto-style1 
      		{
		  	text-align: center;
	  		}
	  	
	  		a { color: inherit; }
			a:link { text-decoration: none;}
			
      </style>
      
   </head>
   
   <body bgcolor = "#B2B1B1">
	
      <div align = "center">
 
		<img alt="myCity" src="header.jpg" width="600px" height="240px">
		
		<br>

        <div style = "width:600px; border: solid 1px #333333; " align = "left">
        
		    <div style = "background-color:#333333; color:#FFFFFF; padding:3px;" align="center"><b>myCity Info</b></div>
				
	            <div style = "margin:30px">
               
	               	<form action = "" method = "post">
	               
	               		<div class="auto-style1">
		                	
		                	<label>City / Locale : </label><input type = "text" name = "city" class = "box"/><br /><br />
		                	
		                	<label>State / Country : </label><input type = "text" name = "state" class = "box" /><br/><br />
		                	
		                	<input type = "submit" value = " Search "/><br />
		                	
		                	<br><br>
		                	
							<p><b>City :</b> <?php echo $myCity ?>, <?php echo $myState ?></p>
						
							<p><b>Latitude  :</b> <?php echo $myLat ?></p>

							<p><b>Longitude :</b> <?php echo $myLon ?></p>
							
							<p><b>Current Temperature :</b> <?php echo $myTemp ?>  &deg; F</p>
							
							<p><b>Currently Feels Like :</b> <?php echo $myFeels ?> &deg; F</p>
							
							<p><b>Current Humidity Index :</b> <?php echo $myHumidty ?></p>
							
							<!-- <p>GeoID Link : <?php echo $myNameID ?></p> -->
												
							<p><b>Hub City Population :</b> <?php echo $myPopulation ?></p>					
												
	               		</div>
               
					</form>
               
            		<div style = "font-size:11px; color:#cc0000; margin-top:10px">
            		
            			<?php echo $error; ?>
            	
            		</div>
					
				</div>
				
			</div>
			
		</div>

	</body>

</html>