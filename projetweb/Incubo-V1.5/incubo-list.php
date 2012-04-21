<?php
include('config.php');
//require 'initList.php';
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
	   
	   
	            <link type="text/css" rel="stylesheet" href="media/css/demo_table.css" />
        <script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>

		 <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
		
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
		
		<script type="text/javascript" language="JavaScript1.2" >
			
			var marker;
			var infowindow;
			var geocoder = new google.maps.Geocoder();
			var _latlng, addr, mapM;
            var map;
            var oTable;

			function currentmarker(mark, objmap){
			    alert("function markerM ... ");
				mapM = objmap;
                _latlng = mark.getPosition();
			}

			function createM(infoW){

				marker.setPosition(null);
				
				var r = infoW;
				var save_marker = new google.maps.Marker({
                  map: mapM,
                  draggable: true,
                  position: _latlng
                });


               var text = [
                  '<div id="InfoText">',
                  '<div class="tabs"><ul><li><a href="#tab1">General</a></li>',
                  '<li><a href="#tab2" id="SV">Street View</a></li></ul>',
                  '<div id="tab1">',
                  infoW["titre"]+'</br>',
                  infoW["adresse"]+'</br>',
                  infoW["date"]+'</br>',
                  infoW["description"]+'</br>',
                  '</div>',  
                  '<div id="tab2">',
                  'Carouselle d\'image',
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
                                
                infowindow.open(mapM, save_marker);
     
			}

			function geocodePosition(pos) {
			    
				document.getElementById('lat').innerHTML = pos.lat();
				document.getElementById('lng').innerHTML = pos.lng();
				 
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
			  //document.getElementById('address').innerHTML = str;
			}

            function createXmlHttpRequest() {
                try {
                    if (typeof ActiveXObject != 'undefined') {
                        return new ActiveXObject('Microsoft.XMLHTTP');
                    } else if (window["XMLHttpRequest"]) {
                        return new XMLHttpRequest();
                    }
                } catch (e) {
                    changeStatus(e);
                }
                return null;
            };

            function downloadUrl(url, callback) {
                var status = -1;
                var request = createXmlHttpRequest();
                if (!request) {
                    return false;
                }
            
                request.onreadystatechange = function() {
                    if (request.readyState == 4) {
                        try {
                            status = request.status;
                        } catch (e) {
                        }
                        if (status == 200) {
                            callback(request.responseText, request.status);
                            request.onreadystatechange = function() {};
                        }
                    }
                }
                request.open('GET', url, true);
                try {
                    request.send(null);
                } catch (e) {
                    changeStatus(e);
                }
            };

            function xmlParse(str) {
              if (typeof ActiveXObject != 'undefined' && typeof GetObject != 'undefined') {
                var doc = new ActiveXObject('Microsoft.XMLDOM');
                doc.loadXML(str);
                return doc;
              }
            
              if (typeof DOMParser != 'undefined') { 
                return (new DOMParser()).parseFromString(str, 'text/xml');
              }
            
              return createElement('div', null);
            }
            
		    function initialize() {

		        var mapOptions = {
		          center: new google.maps.LatLng(47.8688, 2.2195),
		          zoom: 5,
		          mapTypeId: google.maps.MapTypeId.ROADMAP
		        };
		        map = new google.maps.Map(document.getElementById('carte'),mapOptions);
	
		        mapM = map; // externalisation du pointeur map

		        infowindow = new google.maps.InfoWindow();

                downloadUrl("initMarker.php", function(data) { 
                   var xml = xmlParse(data);
                   var markers = xml.documentElement.getElementsByTagName("marker");
                   
                   
                   for (var i = 0; i < markers.length; i++) {
                       
                    createMarker(parseFloat(markers[i].getAttribute("lat")), parseFloat(markers[i].getAttribute("lng")), markers[i].getAttribute('titre'), markers[i].getAttribute('description'));
                   }
                   
                   setTimeout(function() {
                       $("#loader").css("display", "none");
                       $("#carte").css("visibility", "visible");
                   }, 3000);
                   
                  });
              
}

            function createMarker(lat, lng, titre, description){
    
                  var latlng = new google.maps.LatLng(lat, lng);
                  var marker = new google.maps.Marker({
                   position: latlng,
                   map: map,
                   draggable: true
              });
              
              var text = [
                  '<div id="InfoText">',
                  '<div class="tabs"><ul><li><a href="#tab1">General</a></li>',
                  '<li><a href="#tab2" id="SV">Street View</a></li></ul>',
                  '<div id="tab1">',
                  titre+'</br>',
                  description+'</br>',
                  '</div>',  
                  '<div id="tab2">',
                  'Carouselle d\'image',
                  '</div>',
                  '</div>'
                ].join('');
  

               infowindow = new google.maps.InfoWindow({
                   content: text,
                   maxWidth: 500 
                });

                google.maps.event.addListener(marker, 'dblclick', function () {
                    if (infowindow) infowindow.close();
                    infowindow.open(map,marker);
                });
                google.maps.event.addListener(infowindow, 'domready', function () {
                        $("#InfoText").tabs();
                    });
                                

              google.maps.event.addListener(marker, 'dblclick', function() {
                    infowindow.open(map,marker);
                  });
                /*  
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
 */
            }

            window.onresize = function(event) {
                var haut = (window.innerHeight)-210;
                $("#carte").css("height", haut);
            }       
             
            $(document).ready(function() {
                
                    
                    var haut = (window.innerHeight)-210;
                $("#carte").css("height", haut);
                
                oTable = $('#example').dataTable( {
                                "sScrollY": "200px",
                                "bPaginate": false,
                                "bScrollCollapse": true,
                                "sAjaxSource" : 'initList.php'
                                
                } );    
                                
                          
                
                /* Add a click handler to the rows - this could be used as a callback */
               
                $("#example tbody tr").hover( function( e ) {
                    if ( $(this).hasClass('hightlight') ) {
                        $(this).removeClass('hightlight');
                    }
                    else {
                        oTable.$('tr.hightlight').removeClass('hightlight');
                        $(this).addClass('hightlight');
                    }
                });
                
                    $("#example tbody tr").click( function( e ) {
                    if ( $(this).hasClass('row_selected') ) {
                        $(this).removeClass('row_selected');
                    }
                    else {
                        oTable.$('tr.row_selected').removeClass('row_selected');
                        $(this).addClass('row_selected');
                    }
                });
                 
                 
            });

            /* Get the rows which are currently selected */
            function fnGetSelected( oTableLocal )
            {
                return oTableLocal.$('tr.row_selected');
            }

        </script>
	</head>

	<body onload="initialize()">

        <div id="loader">
            
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





        <div class="administration">
            <h3> Liste des sites / lieux </h3>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
         <tr>
             <th>One</th>
             <th>Two</th>
             <th>Three</th>
             <th>Four</th>
         </tr>
     </thead>
     <tbody>
 
     </tbody>
    
</table>

         </div>

            



		<div id="dialog"> 

								<form method="post" action="">				
									<ul>
									<li>
										<div id="lat"></div>
										<div id="lng"></div>
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
							    var infow = new Array();

                                infow["titre"] = $(".titre").val();
                                infow["adresse"] = $("#adresse").text();
                                infow["date"] = $(".date").val();
                                infow["description"] = $(".description").val();
                                infow["lat"] = $("#lat").text();
                                infow["lng"] = $("#lng").text();
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