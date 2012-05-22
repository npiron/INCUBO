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
        <link href="<?php echo $design;?>/style.css" rel="stylesheet" title="Style" />
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
        <script type="text/javascript" language="JavaScript1.2" >
            var marker;
            var infowindow;
            var geocoder = new google.maps.Geocoder();
            var _latlng, addr, mapM;

            var priere = new google.maps.MarkerImage('media/images/prayer.png');
            var cathedral = new google.maps.MarkerImage('media/images/cathedral.png');
            var catholicgrave = new google.maps.MarkerImage('media/images/catholicgrave.png');
            var cemetary = new google.maps.MarkerImage('media/images/cemetary.png');
            var chapel = new google.maps.MarkerImage('media/images/chapel-2.png');
            var church = new google.maps.MarkerImage('media/images/church-2.png');
            var convent = new google.maps.MarkerImage('media/images/convent-2.png');
            var cross = new google.maps.MarkerImage('media/images/cross-2.png');
            var headstone = new google.maps.MarkerImage('media/images/headstone-2.png');
            var japanese = new google.maps.MarkerImage('media/images/japanese-temple.png');
            var jewishgrave = new google.maps.MarkerImage('media/images/jewishgrave.png');
            var mosquee = new google.maps.MarkerImage('media/images/mosquee.png');
            var shintoshrine = new google.maps.MarkerImage('media/images/shintoshrine.png');
            var sikh = new google.maps.MarkerImage('media/images/sikh.png');
            var synagogue = new google.maps.MarkerImage('media/images/synagogue-2.png');
            var templehindu = new google.maps.MarkerImage('media/images/templehindu.png');
            var theravadatemple = new google.maps.MarkerImage('media/images/theravadatemple.png');

            function currentmarker(mark, objmap) {
                //alert("function markerM ... ");
                mapM = objmap;
                _latlng = mark.getPosition();
            }

            function createM(infoW) {

                marker.setPosition(null);

                var r = infoW;
                var save_marker = new google.maps.Marker({
                    map : mapM,
                    draggable : true,
                    position : _latlng
                });

                var text = ['<div id="InfoText">', '<div class="tabs"><ul><li><a href="#tab1">General</a></li>', '<li><a href="#tab2" id="SV">Street View</a></li></ul>', '<div id="tab1">', infoW["titre"] + '</br>', infoW["adresse"] + '</br>', infoW["date"] + '</br>', infoW["description"] + '</br>', '</div>', '<div id="tab2">', 'Carouselle d\'image', '</div>', '</div>'].join('');
                infowindow = new google.maps.InfoWindow({
                    content : text,
                    maxWidth : 500
                });
                alert(infoW["titre"] + infoW["adresse"] + infoW["date"] + infoW["description"] + infoW["lat"] + infoW["lng"]);
                $.ajax({
                    type : "POST",
                    url : "creationM.php",
                    data : {
                        titre : infoW["titre"],
                        adresse : infoW["adresse"],
                        date : infoW["date"],
                        description : infoW["description"],
                        latitude : infoW["lat"],
                        longitude : infoW["lng"]
                    }
                }).done(function(msg) {
                    alert("Data Saved: " + msg);
                });

                google.maps.event.addListener(save_marker, 'dblclick', function() {
                    if(infowindow)
                        infowindow.close();
                    infowindow.open(mapM, save_marker);
                });
                google.maps.event.addListener(infowindow, 'domready', function() {
                    $("#InfoText").tabs();
                });

                infowindow.open(mapM, save_marker);

            }

            function geocodePosition(pos) {

                document.getElementById('lat').innerHTML = pos.lat();
                document.getElementById('lng').innerHTML = pos.lng();

                geocoder.geocode({
                    latLng : pos
                }, function(responses) {
                    if(responses && responses.length > 0) {
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
                document.getElementById('info').innerHTML = [latLng.lat(), latLng.lng()].join(', ');
            }

            function updateMarkerAddress(str) {
                document.getElementById('adresse').innerHTML = "Adresse : " + str + "<br>";
                //document.getElementById('address').innerHTML = str;
            }

            function initialize() {

                var mapOptions = {
                    center : new google.maps.LatLng(47.8688, 2.2195),
                    zoom : 5,
                    mapTypeId : google.maps.MapTypeId.ROADMAP
                };
                var map = new google.maps.Map(document.getElementById('carte'), mapOptions);
                mapM = map;
                // externalisation du pointeur map
                var input = document.getElementById('searchTextField');
                var autocomplete = new google.maps.places.Autocomplete(input);

                autocomplete.bindTo('bounds', map);
                infowindow = new google.maps.InfoWindow();
                marker = new google.maps.Marker({
                    map : map,
                    draggable : true,
                    position: new google.maps.LatLng(47.8688, 2.2195)
                });

                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    infowindow.close();
                    var place = autocomplete.getPlace();
                    if(place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(10);
                        // Why 17? Because it looks good.
                        geocodePosition(marker.getPosition());

                    }

                    marker.setPosition(place.geometry.location);

                    var address = '';
                    if(place.address_components) {
                        address = [(place.address_components[0] && place.address_components[0].short_name || ''), (place.address_components[1] && place.address_components[1].short_name || ''), (place.address_components[2] && place.address_components[2].short_name || '')].join(' ');
                    }

                    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                    infowindow.open(map, marker);
                    updateMarkerPosition(marker.getPosition());
                    geocodePosition(marker.getPosition());
                    currentmarker(marker, map);

                });

                google.maps.event.addListener(marker, 'dblclick', function() {
                    infowindow.open(map, marker);
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
                setTimeout(function() {
                    $("#loader").css("display", "none");
                    $("#carte").css("visibility", "visible");
                }, 3000);
            }
        </script>
    </head>
    <body onload="initialize()">
        <div id="volet">
            <h1> Liste des sites </h1>
            <br />
            <input id="searchTextField" type="text" size="150">
            <br />
            <div id="markerStatus">
                Click and drag the marker
            </div>
            <div id="info"></div>
            <div id="adresse"></div>
            <br />
            <button id="creer" >
                Créer ce site
            </button>
            <div id="markers" style="position:absolute; top:70px; left:680px; width:200px; height:100px;">
                <div id="m1" class="drag" style="position:absolute; left:0; width:32px; height:32px;"><img src="http://maps.gstatic.com/mapfiles/ms/icons/ltblue-dot.png" width="32" height="32" alt="" />
                </div>
                <div id="m2" class="drag" style="position:absolute; left:50px; width:32px; height:32px;"><img src="http://maps.gstatic.com/mapfiles/ms/icons/orange-dot.png" width="32" height="32" alt="" />
                </div>
                <div id="m3" class="drag" style="position:absolute; left:100px; width:32px; height:32px;"><img src="http://maps.gstatic.com/mapfiles/ms/icons/pink-dot.png" width="32" height="32" alt="" />
                </div>
            </div>
        </div>
        <?php
if(isset($_SESSION['username']))
{
        ?>
        <div id="top">
            <a href="edit_infos.php">Modifier</a>
            <a href="deconnection.php">Déconnection</a>
            <span style="float: right; padding-right: 10px;">Bienvenue <?php 
                echo $_SESSION['username'];
                ?></span>
        </div>
        <?php

        }
        else
        {
        //Sinon, on lui donne un lien pour sinscrire et un autre pour se connecter
        ?>
        <div id="top">
            <ul>
                <li><img id="planete" src="default/images/planete.gif"></img>
                </li>
                <li>
                    <span id="incubo">Incubo </span>
                </li>
                <li>
                    <a id="menu" class="site" href="connexion.php" class="connection">Connection</a>
                </li>
                <li>
                    <a id="menu" href="profile.php">Administration</a>
                </li>
                <li>
                    <a id="menu" href="sign_up.php">S'inscrire</a>
                </li>
            </ul>
        </div>
        <?php
        }
        ?> <div id="carte" style="width:100%; height:100%"></div>
        <div id="dialog">
            <form method="post" action="">
                <ul>
                    <li>
                        <a href="#general">General</a>
                    </li>
                    <li>
                        <a href="#image_marker">Image marker</a>
                    </li>
                    <li>
                        <a href="#image">Ressources</a>
                    </li>
                </ul>
                <ul>
                    <div id="general">
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
                    </div>
                    <div id="image_marker">
                        <div id="icone">
                            <table>
                                <tr>
                                    <td><img src="media/images/prayer.png" />
                                    <input name="icon" id="radio" type="radio" />
                                    prayer</td>
                                </tr>
                                <tr>
                                    <td><img src="media/images/cathedral.png" />
                                    <input name="icon" id="radio" type="radio" />
                                    cathedral</td>
                                </tr>
                                <tr>
                                    <td><img src="media/images/catholicgrave.png" />
                                    <input name="icon" id="radio" type="radio" />
                                    catholicgrave</td>
                                </tr>
                                <tr>
                                    <td><img src="media/images/cemetary.png" />
                                    <input name="icon"id="radio" type="radio" />
                                    cemetary</td>
                                </tr>
                                <tr>
                                    <td><img src="media/images/chapel-2.png" />
                                    <input name="icon" id="radio" type="radio" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="media/images/church-2.png" />
                                    <input name="icon" id="radio" type="radio" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="media/images/convent-2.png" />
                                    <input name="icon" id="radio" type="radio" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="media/images/cross-2.png" />
                                    <input name="icon" id="radio"  type="radio" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div id="image">
                 
                            <tr><select id="fichier" multiple="multiple" size="10"></select></tr>

                        </div>

                        </ul>
                        </form>
                        </div>

                        </div>
                        <script type="text/javascript">
                        
                                

                        
                            $("a.site").fancybox({
                                'hideOnContentClick' : true,
                                'padding' : 0,
                                'overlayColor' : '#D3D3D3',
                                'transitionIn' : 'elastic',
                                'transitionOut' : 'elastic',
                                'overlayOpacity' : 0.7,
                                'zoomSpeedIn' : 3500,
                                'zoomSpeedOut' : 3500,
                                'width' : 425,
                                'height' : 350,
                                'type' : 'iframe'
                            });
                            


                            $("#creer").click(function() {
                                $("dialog").css("display", "block");
                                $("#dialog").tabs();
                                $("#dialog").dialog({
                                    modal : true,
                                    draggable : true,
                                    resizable : true,
                                    width : 505,
                                    height : 700,
                                    buttons : {
                                        Ok : function() {
                                            var infow = new Array();

                                            infow["titre"] = $(".titre").val();
                                            infow["adresse"] = $("#adresse").text();
                                            infow["date"] = $(".date").val();
                                            infow["description"] = $(".description").val();
                                            infow["lat"] = $("#lat").text();
                                            infow["lng"] = $("#lng").text();
                                            createM(infow);
                                            $(this).dialog("close");
                                        },
                                        "Annuler" : function() {
                                            $(this).dialog("close");
                                        }
                                    }
                                });
                            });

                            $("#top #menu").mouseover(function() {
                                $(this).animate({
                                    backgroundColor : "black"
                                }, 200);
                            }).mouseout(function() {
                                $(this).animate({
                                    backgroundColor : "darkgrey"
                                }, 200);
                            });
                            $(function() {
                                $("#infobulle").tabs();
                            });

                         </script>
               
                         <?php
                $dirname = 'media/images/marker-image/';
                $dir = opendir($dirname); 
                
                while($file = readdir($dir)) {
                    if($file != '.' && $file != '..' && !is_dir($dirname.$file))
                    {
                        //echo '<a href="'.$dirname.$file.'">'.$file.'</a>';
                        echo '
                        
                        <script type="text/javascript">$(\'#fichier\').append(new Option("'.$file.'", "'.$file.'", false, false));</script>
                        
                        ';

                      
                    }
                }
                
                closedir($dir);
            ?>
                        
                       
                        </body>
                        </html>