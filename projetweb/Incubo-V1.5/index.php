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
			
			var marker;
			var infowindow;
			var geocoder = new google.maps.Geocoder();
			var _latlng, addr, mapM;
            
            
            
            
			function currentmarker(mark, objmap){
			    alert("function markerM ... ");
				mapM = objmap;
                _latlng = mark.getPosition();
			}

			function createM(infoW){

				marker.setPosition(null);
				alert("coucou");
				var save_marker = new google.maps.Marker({
                  map: mapM,
                  draggable: true,
                  position: _latlng
                });
               // alert(infoW["titre"]);
               
               var text = [
                  '<div id="InfoText">',
                  '<div class="tabs"><ul><li><a href="#tab1">General</a></li>',
                  '<li><a href="#tab2" id="SV">Street View</a></li></ul>',
                  '<div id="tab1">',
                  '</div>',
                  '<div id="tab2">',
                  '</div>',
                  '</div>'
                ].join('');
  
               infowindow = new google.maps.InfoWindow({
                   content: text,
                   maxWidth: 500 
                });

                
                google.maps.event.addListener(save_marker, 'dblclick', function () {
                    if (infowindow) infowindow.close();
                    infowindow.open(mapM,save_marker);
                });
                google.maps.event.addListener(infowindow, 'domready', function () {
                        $("#InfoText").tabs();
                    });
                                //$("#infobulle").css("display", "block");
                infowindow.open(mapM, save_marker);
                
                /*google.maps.event.addListener(save_marker, 'dblclick', function() {
                    infowindow.open(mapM,save_marker);
                  });*/
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

		        infowindow = new google.maps.InfoWindow();

		        marker = new google.maps.Marker({
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
		          currentmarker(marker, map);

		        });

                  
  
                 google.maps.event.addListener(marker, 'dblclick', function() {
                    infowindow.open(map,marker);
                  });
				  
				  google.maps.event.addListener(marker, 'drag', function() {
				    updateMarkerStatus('Dragging...');
				    updateMarkerPosition(marker.getPosition());
				  });
				  
				  google.maps.event.addListener(marker, 'dragend', function() {
				    updateMarkerStatus('Drag ended');
				    geocodePosition(marker.getPosition());
				    currentmarker(marker, map);
				  });
				  
				  google.maps.event.addListener(marker, 'dragstart', function() {
                    //updateMarkerAddress('Dragging...');
                  });
}


		</script>
	</head>

	<body onload="initialize()">




		<div id="volet">
    		<h1> Liste des sites </h1>
    		<br />
    		<input id="searchTextField" type="text" size="150">  <br />                  
            <div id="markerStatus">Click and drag the marker.</div>
            <div id="info"></div>
            <div id="address"></div><br />
            <button id="creer" >Créer ce site</button>
		</div>


		<?php
		if(isset($_SESSION['username']))
		            {
		                ?>
		                <div id="top">
		                    <a href="edit_infos.php">Modifier mes informations personnelles</a>
		                    <a href="deconnection.php">Déconnection</a>
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
									<ul>
									<li>
										<div id="latlong">ici</div>
										<div id="adresse"></div>
										<label>Titre du site </label>
										<div>
											<input class="titre" name="element_1" type="text" maxlength="255" value=""/> 
										</div> 
										<label>Adresse du site </label>
										<div>
											<input class="adresse" name="element_2" type="text" maxlength="255" value=""/> 
										</div> 
										
										
										<label>Date </label>
		                                  <textarea class="date"></textarea>
										<label>Description </label>
										<div>
											<textarea class="description"></textarea> 
										</div> 
									</li>
									</ul>
								</form>
		</div> 
		
		<div id="infobulle">
                <ul>
                    <li><a href="#tabs-1">Nunc tincidunt</a></li>
                    <li><a href="#tabs-2">Proin dolor</a></li>
                    <li><a href="#tabs-3">Aenean lacinia</a></li>
                </ul>
                <div id="tabs-1">
                    <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                </div>
                <div id="tabs-2">
                    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
                </div>
                <div id="tabs-3">
                    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
                    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
                </div>
		</div>
									
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
				$("dialog").css("display", "block");
				$( "#dialog" ).dialog({
						modal: true,
						title: "création",
						draggable : true,
						resizable : true,
						width: 505,
						buttons: {
							Ok: function() {
                                var infow = new Object();
                                infow.titre = $(".titre").val();
                                infow.adresse = $(".adresse").val();
                                infow.date = $(".date").val();
                                infow.description = $(".description").val();
                                
							    createM(infow);
								$(this).dialog("close");
							}
						}
					});
				});

			
				$("#top #menu").mouseover(function() {
	  				$(this).animate({backgroundColor:"black" },  200);
 				}).mouseout(function() {
	  				$(this).animate({backgroundColor:"darkgrey" },  200);
 				});
 				
 				$(function() {
                    $("#infobulle").tabs();
                });


		</script>
	</body>
</html>