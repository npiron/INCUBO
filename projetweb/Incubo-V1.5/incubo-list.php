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
                alert(infoW["titre"]+infoW["adresse"]+infoW["date"]+infoW["description"]+infoW["lat"]+infoW["lng"]);
                $.ajax({
                      type: "POST",
                      url: "creationM.php",
                      data: {
                          titre: infoW["titre"],
                          adresse: infoW["adresse"],
                          date: infoW["date"],
                          description: infoW["description"],
                          latitude: infoW["lat"],
                          longitude: infoW["lng"]
                         }
                    }).done(function( msg ) {
                      alert( "Data Saved: " + msg );
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

        var oTable;
 
$(document).ready(function() {
    
        var haut = (window.innerHeight)-358;
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

window.onresize = function(event) {
    var haut = (window.innerHeight)-358;
    $("#carte").css("height", haut);
}
        
        
        
            
        </script>
	</head>

	<body onload="initialize()">

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
            <h1> Liste des sites / lieux </h1>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
         <tr>
             <th>One</th>
             <th>Two</th>
             <th>Three</th>
             <th>Four</th>
             <th>Five</th>
             <th>Six</th>
             <th>Seven</th>
         </tr>
     </thead>
     <tbody>
 
     </tbody>
    
</table>
    
           
<table style="display: none;" cellspacing="0" border="0" class="displayee" id="exampleee">
    <thead>
        <tr>
            <th>Rendering engine</th>
            <th>Browser</th>
            <th>Platform(s)</th>
            <th>Engine version</th>
            <th>CSS grade</th>
        </tr>
    </thead>

    <tbody>
        <tr class="odd gradeX">
            <td>Trident</td>
            <td>Internet
                 Explorer 4.0</td>
            <td>Win 95+</td>
            <td class="center">4</td>
            <td class="center">X</td>
        </tr>
        <tr class="odd gradeC">
            <td>Trident</td>
            <td>Internet
                 Explorer 5.0</td>
            <td>Win 95+</td>
            <td class="center">5</td>
            <td class="center">C</td>
        </tr>
        <tr class="odd gradeA">
            <td>Trident</td>
            <td>Internet
                 Explorer 5.5</td>
            <td>Win 95+</td>
            <td class="center">5.5</td>
            <td class="center">A</td>
        </tr>
        <tr class="odd gradeA">
            <td>Trident</td>
            <td>Internet
                 Explorer 6</td>
            <td>Win 98+</td>
            <td class="center">6</td>
            <td class="center">A</td>
        </tr>
        <tr class="odd gradeA">
            <td>Trident</td>
            <td>Internet Explorer 7</td>
            <td>Win XP SP2+</td>
            <td class="center">7</td>
            <td class="center">A</td>
        </tr>
        <tr class="odd gradeA">
            <td>Trident</td>
            <td>AOL browser (AOL desktop)</td>
            <td>Win XP</td>
            <td class="center">6</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Firefox 1.0</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.7</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Firefox 1.5</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.8</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Firefox 2.0</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.8</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Firefox 3.0</td>
            <td>Win 2k+ / OSX.3+</td>
            <td class="center">1.9</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Camino 1.0</td>
            <td>OSX.2+</td>
            <td class="center">1.8</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Camino 1.5</td>
            <td>OSX.3+</td>
            <td class="center">1.8</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Netscape 7.2</td>
            <td>Win 95+ / Mac OS 8.6-9.2</td>
            <td class="center">1.7</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Netscape Browser 8</td>
            <td>Win 98SE+</td>
            <td class="center">1.7</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Netscape Navigator 9</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.8</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.0</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.1</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.1</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.2</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.2</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.3</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.3</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.4</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.4</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.5</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.5</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.6</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.6</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.7</td>
            <td>Win 98+ / OSX.1+</td>
            <td class="center">1.7</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.8</td>
            <td>Win 98+ / OSX.1+</td>
            <td class="center">1.8</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Seamonkey 1.1</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.8</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Gecko</td>
            <td>Epiphany 2.20</td>
            <td>Gnome</td>
            <td class="center">1.8</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Webkit</td>
            <td>Safari 1.2</td>
            <td>OSX.3</td>
            <td class="center">125.5</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Webkit</td>
            <td>Safari 1.3</td>
            <td>OSX.3</td>
            <td class="center">312.8</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Webkit</td>
            <td>Safari 2.0</td>
            <td>OSX.4+</td>
            <td class="center">419.3</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Webkit</td>
            <td>Safari 3.0</td>
            <td>OSX.4+</td>
            <td class="center">522.1</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Webkit</td>
            <td>OmniWeb 5.5</td>
            <td>OSX.4+</td>
            <td class="center">420</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Webkit</td>
            <td>iPod Touch / iPhone</td>
            <td>iPod</td>
            <td class="center">420.1</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Webkit</td>
            <td>S60</td>
            <td>S60</td>
            <td class="center">413</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 7.0</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 7.5</td>
            <td>Win 95+ / OSX.2+</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 8.0</td>
            <td>Win 95+ / OSX.2+</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 8.5</td>
            <td>Win 95+ / OSX.2+</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 9.0</td>
            <td>Win 95+ / OSX.3+</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 9.2</td>
            <td>Win 88+ / OSX.3+</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 9.5</td>
            <td>Win 88+ / OSX.3+</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Opera for Wii</td>
            <td>Wii</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Nokia N800</td>
            <td>N800</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>Presto</td>
            <td>Nintendo DS browser</td>
            <td>Nintendo DS</td>
            <td class="center">8.5</td>
            <td class="center">C/A<sup>1</sup></td>
        </tr>
        <tr class="gradeC">
            <td>KHTML</td>
            <td>Konqureror 3.1</td>
            <td>KDE 3.1</td>
            <td class="center">3.1</td>
            <td class="center">C</td>
        </tr>
        <tr class="gradeA">
            <td>KHTML</td>
            <td>Konqureror 3.3</td>
            <td>KDE 3.3</td>
            <td class="center">3.3</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeA">
            <td>KHTML</td>
            <td>Konqureror 3.5</td>
            <td>KDE 3.5</td>
            <td class="center">3.5</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeX">
            <td>Tasman</td>
            <td>Internet Explorer 4.5</td>
            <td>Mac OS 8-9</td>
            <td class="center">-</td>
            <td class="center">X</td>
        </tr>
        <tr class="gradeC">
            <td>Tasman</td>
            <td>Internet Explorer 5.1</td>
            <td>Mac OS 7.6-9</td>
            <td class="center">1</td>
            <td class="center">C</td>
        </tr>
        <tr class="gradeC">
            <td>Tasman</td>
            <td>Internet Explorer 5.2</td>
            <td>Mac OS 8-X</td>
            <td class="center">1</td>
            <td class="center">C</td>
        </tr>
        <tr class="gradeA">
            <td>Misc</td>
            <td>NetFront 3.1</td>
            <td>Embedded devices</td>
            <td class="center">-</td>
            <td class="center">C</td>
        </tr>
        <tr class="gradeA">
            <td>Misc</td>
            <td>NetFront 3.4</td>
            <td>Embedded devices</td>
            <td class="center">-</td>
            <td class="center">A</td>
        </tr>
        <tr class="gradeX">
            <td>Misc</td>
            <td>Dillo 0.8</td>
            <td>Embedded devices</td>
            <td class="center">-</td>
            <td class="center">X</td>
        </tr>
        <tr class="gradeX">
            <td>Misc</td>
            <td>Links</td>
            <td>Text only</td>
            <td class="center">-</td>
            <td class="center">X</td>
        </tr>
        <tr class="gradeX">
            <td>Misc</td>
            <td>Lynx</td>
            <td>Text only</td>
            <td class="center">-</td>
            <td class="center">X</td>
        </tr>
        <tr class="gradeC">
            <td>Misc</td>
            <td>IE Mobile</td>
            <td>Windows Mobile 6</td>
            <td class="center">-</td>
            <td class="center">C</td>
        </tr>
        <tr class="gradeC">
            <td>Misc</td>
            <td>PSP browser</td>
            <td>PSP</td>
            <td class="center">-</td>
            <td class="center">C</td>
        </tr>
        <tr class="gradeU">
            <td>Other browsers</td>
            <td>All others</td>
            <td>-</td>
            <td class="center">-</td>
            <td class="center">U</td>
        </tr>
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