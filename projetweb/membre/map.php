<?php
include('config.php')
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">

	<head>
		<title>test API Google</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
		<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>

			<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

		 <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
		
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
		
		<script type="text/javascript" language="JavaScript1.2" >
			
			
			
			var geocoder = new google.maps.Geocoder();
			var lat, lng, addr, mapM;

			function markerM(mark, objmap){
				mark = mark.getPosition();
				lat = mark.lat();
				lng = mark.lng();
				mapM = objmap;
				createM();
			}

			function createM(){

				alert(lat + " " + lng + mapM);

			}

			function geocodePosition(pos) {
				document.getElementById('latlong').innerHTML = "Latitude : " + pos.lat() + "<br>Longitude : "+ pos.lng() + "<br>";
			  geocoder.geocode({
			    latLng: pos
			  }, function(responses) {
			    if (responses && responses.length > 0) {
			      updateMarkerAddress(responses[0].formatted_address);
			    } else {
			      updateMarkerAddress('Vous dans l\'eau ?');
			    }
			  });
			}

			function updateMarkerStatus(str) {
			  document.getElementById('markerStatus').innerHTML = str;
			}

			function updateMarkerPosition(latLng) {
			  document.getElementById('info').innerHTML = [
			    latLng.lat(),
			    latLng.lng()
			  ].join(', ');
			}

			function updateMarkerAddress(str) {
			  document.getElementById('adresse').innerHTML = "Adresse : " + str + "<br>";
			  document.getElementById('address').innerHTML = str;
			}

		    function initialize() {

		        var mapOptions = {
		          center: new google.maps.LatLng(47.8688, 2.2195),
		          zoom: 5,
		          mapTypeId: google.maps.MapTypeId.ROADMAP
		        };
		        var map = new google.maps.Map(document.getElementById('carte'),mapOptions);
		        mapM = map; // externalisation du pointeur map
		        var input = document.getElementById('searchTextField');
		        var autocomplete = new google.maps.places.Autocomplete(input);

		        autocomplete.bindTo('bounds', map);

		        var infowindow = new google.maps.InfoWindow();

		        var marker = new google.maps.Marker({
		          map: map,
    			  draggable: true
		        });


		        google.maps.event.addListener(autocomplete, 'place_changed', function() {
		          infowindow.close();
		          var place = autocomplete.getPlace();
		          if (place.geometry.viewport) {
		            map.fitBounds(place.geometry.viewport);
		          } 
		          else {
		            map.setCenter(place.geometry.location);
		            map.setZoom(10);  // Why 17? Because it looks good.
		            geocodePosition(marker.getPosition());

		          }

		          marker.setPosition(place.geometry.location);

		          var address = '';
		          if (place.address_components) {
		            address = [(place.address_components[0] &&
		                        place.address_components[0].short_name || ''),
		                       (place.address_components[1] &&
		                        place.address_components[1].short_name || ''),
		                       (place.address_components[2] &&
		                        place.address_components[2].short_name || '')
		                      ].join(' ');
		          }

		          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
		          infowindow.open(map, marker);
		          updateMarkerPosition(marker.getPosition());
		          geocodePosition(marker.getPosition());

		        });

  
				  google.maps.event.addListener(marker, 'dragstart', function() {
				    //updateMarkerAddress('Dragging...');
				  });
				  
				  google.maps.event.addListener(marker, 'drag', function() {
				    updateMarkerStatus('Dragging...');
				    updateMarkerPosition(marker.getPosition());
				  });
				  
				  google.maps.event.addListener(marker, 'dragend', function() {
				    updateMarkerStatus('Drag ended');
				    geocodePosition(marker.getPosition());
				  });
}


		</script>
	</head>

	<body onload="initialize()">




		<div id="volet">
			dzadadazda
		<h1> Liste des site </h1>
		</div>


		<?php
		if(isset($_SESSION['username']))
		            {
		                ?>
		                <div id="top">
		                    <a href="edit_infos.php">Modifier mes informations personnelles</a>
		                    <a href="deconnection.php">Déconnection</a>
						 	<input id="searchTextField" type="text" size="50">				 	
							<span id="markerStatus">Click and drag the marker.</span>
						    <span id="info"></span>
						    <span id="address"></span>
						    <button id="creer" >Créer ce site</button>
						</div>
		                <?php

		            }
		            else
		            {
		                //Sinon, on lui donne un lien pour sinscrire et un autre pour se connecter
		                ?>
		                	<div id="top">
		                		<ul>
		                			<li><img id="planete" src="default/images/planete.gif"></img></li>
		                			<li><span id="incubo">Incubo </span></li>
			                    	<li><a id="menu" class="site" href="connexion.php?iframe" class="connection">Connection</a></li>
			                    	<li><a id="menu" href="profile.php">Administration</a></li>
			                    	<li><a id="menu" href="sign_up.php">S'inscrire</a></li>
		                    	</ul>
		                	</div>
		                <?php
		             }
		?>
		<div id="carte" style="width:100%; height:100%"></div>
		<div id="dialog"> 

								<form method="post" action="">
									<div class="form_description">
									</div>						
									<ul >
									<li>
										<div id="latlong">ici</div>
										<div id="adresse"></div>
										<label class="description">Titre du site </label>
										<div>
											<input name="element_1" type="text" maxlength="255" value=""/> 
										</div> 
										<label class="description">Adresse du site </label>
										<div>
											<input name="element_2" type="text" maxlength="255" value=""/> 
										</div> 
										<label>Type de site </label>
										<div>
										<select> 
											<option value="" selected="selected"></option>
												<option value="1" >Sanctuaire</option>
												<option value="2" >lieux de culte</option>
										</select>
										</div> 
										<label>Date </label>
		 
										<label>Description </label>
										<div>
											<textarea></textarea> 
										</div> 
									</li>
									</ul>
								</form>	
									
		</div>
		<script type="text/javascript">

			$("a.site").fancybox({ 			
				'hideOnContentClick'	: true,
				'padding'				: 0,
				'overlayColor'			:'#D3D3D3',
				'transitionIn'			:'elastic',
				'transitionOut'			:'elastic',
				'overlayOpacity'		: 0.7,
				'zoomSpeedIn'			: 1500,
				'zoomSpeedOut'			: 1500,
				'width'				: 450,
				'height'			: 400,
				'type'				:'iframe',
		});

			$("#creer").click(function (){
				/*var coor = marker.getPosition();
				var ll = coor.lat() . coor.lng();
				$("#latlong").innerHTML = ll;*/
				//alert(marker.getPosition());
				$("dialog").css("display", "block");
				$( "#dialog" ).dialog({
						modal: true,
						title: "création",
						draggable : true,
						resizable : true,
						width: 505,
						buttons: {
							Ok: function() {
								$( this ).dialog( "close" );
							}
						}
					});
				});

			
				$("#top #menu").mouseover(function() {
	  				$(this).animate({backgroundColor:"black" },  200);
 				}).mouseout(function() {
	  				$(this).animate({backgroundColor:"darkgrey" },  200);
 				});


		</script>
	</body>
</html>