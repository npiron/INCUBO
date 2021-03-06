<?php
include('config.php');
//require 'initList.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">

	<head>
		<title>test API Google</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>


	   <script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	   <script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	   <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	   
	   
	   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/smoothness/jquery-ui.css" />

	   

<link rel="stylesheet" type="text/css" media="screen" href="media/jqgrid/css/ui.jqgrid.css" />
 
<script src="media/jqgrid/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="media/jqgrid/js/i18n/grid.locale-fr.js" type="text/javascript"></script>
<script src="media/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
	   
	   
	    <link type="text/css" rel="stylesheet" href="media/css/demo_table.css" />
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
                  }, 1000);
                       
                
                   
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
                    largeurbox = 400;
                var larg = ((window.innerWidth)/2)-(largeurbox/2);
                $("#dialog").css("display", "block");
                    $("#dialog").dialog({
                            modal: true,
                            draggable : true,
                            resizable : true,
                            position : [eval(larg),100],
                            width: largeurbox
                            

                    });
                });
                
                
                google.maps.event.addListener(infowindow, 'domready', function () {
                        $("#InfoText").tabs();
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
                
        /*        oTable = $('#example').dataTable( {
                     "sScrollY": "215px",
                    "bPaginate": true,
                    "sPaginationType": "full_numbers",
                    "bFilter": true,
                    "bSort": true,
                    "bAutoWidth": false,
                    "sAjaxSource" : 'initList.php',
                    
                            "sScrollX": "100%",
        "sScrollXInner": "110%",
        "bScrollCollapse": true
                                
                } );    
                                
                    */
                   
                   jQuery("#list2").jqGrid({
                        url:'initList.php',
                        datatype: "json",
                        autowidth: true,
                        colNames:['ID','codeSite', 'latitude', 'longitude','titre','adresse','commune', 'departement',
                         'lieuxDit', 'date', 'description', 'auteurFiche', 'operation', 'structArcheo', 'mobilierArcheo',
                          'sourcesHisto', 'sourcesEpigra', 'datation', 'piecesJointes'],
                        colModel:[
                            {name:'id',index:'id', width:90},
                            {name:'codeSite',index:'codeSite', width:90},
                            {name:'latitude',index:'latitude', width:300},
                            {name:'longitude',index:'longitude', width:300},
                            {name:'titre',index:'titre', width:300},      
                            {name:'adresse',index:'adresse', width:300},       
                            {name:'commune',index:'commune', width:300},  
                            {name:'departement',index:'departement', width:300},
                            {name:'lieuxDit',index:'lieuDit', width:300},
                            {name:'date',index:'date', width:300},
                            {name:'description',index:'description', width:300},
                            {name:'auteurFiche',index:'auteurFiche', width:300},
                            {name:'operation',index:'operation', width:300},
                            {name:'structArcheo',index:'structArcheo', width:300},
                            {name:'mobilierArcheo',index:'mobilierArcheo', width:300},
                            {name:'sourcesHisto',index:'sourcesHisto', width:300},
                            {name:'sourcesEpigra',index:'sourcesEpigra', width:300},
                            {name:'datation',index:'datation', width:300},
                            {name:'piecesJointes',index:'piecesJointes', width:300}    
                        ],
                        rowNum:10,
                        rowList:[10,20,30],
                        pager: '#pager2',
                        sortname: 'id',
                        viewrecords: true,
                        sortorder: "desc",
                        caption:"List des Sites"
                    });
                    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false});      
                
                /* Add a click handler to the rows - this could be used as a callback */
               
                $("#example tbody tr").live('hover', function () {
     
                    if ( $(this).hasClass('hightlight') ) {
                        $(this).removeClass('hightlight');
                    }
                    else {
                        oTable.$('tr.hightlight').removeClass('hightlight');
                        $(this).addClass('hightlight');
                    }
                    
                });
                
                $("#example tbody tr").live('click', function () {
                    
                    var nR = $('#example').dataTable().fnGetPosition(this);     
                    var oT = document.getElementById('example');
                    nR++;
                    //var rowLength = oT.rows.length;
                    var lat = oT.rows[nR].cells[2].childNodes[0].data;
                    var lng = oT.rows[nR].cells[3].childNodes[0].data;
                    //alert(lat + "   " + lng);
                    
                    var center = new google.maps.LatLng(lat, lng);
                    map.setCenter(center);
                    map.setZoom(10);
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


        <div id="loader">
            
        </div>

		<?php
		if(isset($_SESSION['username']))
		            {
		                ?>
		                <div id="top">
		                    <a href="edit_infos.php">Administration</a>
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
			                    	<a href="deconnection.php">Supprimer</a>
                                    <a href="deconnection.php">Modifier</a>
		                    	</ul>
		                	</div>
		                <?php
		             }
		?>
		
		<div id="carte" style="width:100%; height:100%"></div>





        <div class="administration">
       
<table id="list2"></table>
<div id="pager2"></div>

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

			/*$("#creer").click(function (){
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
*/



			
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