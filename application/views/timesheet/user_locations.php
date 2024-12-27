<?php
$session = $this->session->userdata('username');
$date = date('Y-m-d');
if(isset($_REQUEST['date']) && !empty($_REQUEST['date'])){
    $date = $_REQUEST['date'];
}
$location_query = $this->db->query("select e.first_name, e.last_name, e.profile_picture, a.clock_in_location, a.clock_in, a.clock_out from xin_employees e, xin_attendance_time a where e.user_id=a.employee_id and e.root_id='".$session['root_id']."' and a.attendance_date='".$date."' and clock_in_location!='' GROUP by a.employee_id order by a.time_attendance_id desc");

$map_data = array();
$slno = 1;
foreach($location_query->result() as $user_locations){
    $lat_long = explode(',',$user_locations->clock_in_location);
    if(isset($lat_long[1])){
        $map_data[$slno] = array(
            'full_name' => $user_locations->first_name.' '.$user_locations->last_name, 
            'profile_picture' => $user_locations->profile_picture, 
            'lat' => $lat_long[0], 
            'lon' => $lat_long[1], 
            'in_time' => date('D d M h:i A', strtotime($user_locations->clock_in)),
            );
        if(!empty($user_locations->clock_out)){
            $map_data[$slno]['out_time'] = date('D d M h:i A', strtotime($user_locations->clock_in));
        }
        $slno++;
    }
}

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
  #map {
    height: 100%;
  }
</style>

<div class="row m-b-1">
  <div class="col-md-12">
    <div class="box box-block bg-white" style="height:1100px;">
      <h2><strong>User Locations</strong></span> </h2>

      
      
      <div id="map" class="col-lg-12" style="height:1000px;"></div>
        <script>
              var geocoder;
              var map;
              var markers = [];
              function initMap() {
        <?php
        foreach($map_data as $key=>$row)
        { 
        ?>
            var project<?php echo $key; ?> = {
    		info: '<div style="width:200px; margin-left:7px;">\
				   <?php if(!empty($row["profile_picture"])){ ?> <img src="/uploads/profile/<?php echo $row["profile_picture"]; ?>" width="200" style="border-radius:60px;"/> <?php } ?><br><br>\
				   <h4 style="color: #f18131;"><?php echo $row["full_name"]; ?></h4><p><b>In Time</b> - <?php echo $row['in_time']; ?></p> <?php if(!empty($row["out_time"])){ ?> <p><b>Out Time</b> - <?php echo $row['out_time']; ?></p> <?php } ?></div>',
    		lat: <?php echo $row["lat"]; ?>,
    		long: <?php echo $row["lon"]; ?>
    	    };
            
        <?php
        }
        ?>

	var locations = [
	  <?php 
	  $slno = count($map_data);
	  for($i=1; $i<=$slno; $i++)
	  {
	      echo '[project'.$i.'.info, project'.$i.'.lat, project'.$i.'.long, '.($i-1).'],';
	  }
	  ?>
    ];
        
        <?php
        if(!empty($where_q))
        {
        ?>
            var bounds = new google.maps.LatLngBounds();
            map = new google.maps.Map(document.getElementById('map'), { 
                maxZoom: 17,
                styles: [
                  {
                    "elementType": "geometry",
                    "stylers": [
                      {
                        "color": "#f5f5f5"
                      }
                    ]
                  },
                  {
                    "elementType": "labels.icon",
                    "stylers": [
                      {
                        "visibility": "off"
                      }
                    ]
                  },
                  {
                    "elementType": "labels.text.fill",
                    "stylers": [
                      {
                        "color": "#616161"
                      }
                    ]
                  },
                  {
                    "elementType": "labels.text.stroke",
                    "stylers": [
                      {
                        "color": "#f5f5f5"
                      }
                    ]
                  },
                  {
                    "featureType": "administrative.land_parcel",
                    "elementType": "labels",
                    "stylers": [
                      {
                        "visibility": "off"
                      }
                    ]
                  },
                  {
                    "featureType": "administrative.land_parcel",
                    "elementType": "labels.text.fill",
                    "stylers": [
                      {
                        "color": "#bdbdbd"
                      }
                    ]
                  },
                  {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                      {
                        "color": "#eeeeee"
                      }
                    ]
                  },
                  {
                    "featureType": "poi",
                    "elementType": "labels.text",
                    "stylers": [
                      {
                        "visibility": "off"
                      }
                    ]
                  },
                  {
                    "featureType": "poi",
                    "elementType": "labels.text.fill",
                    "stylers": [
                      {
                        "color": "#757575"
                      }
                    ]
                  },
                  {
                    "featureType": "poi.business",
                    "stylers": [
                      {
                        "visibility": "off"
                      }
                    ]
                  },
                  {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [
                      {
                        "color": "#e5e5e5"
                      }
                    ]
                  },
                  {
                    "featureType": "poi.park",
                    "elementType": "labels.text",
                    "stylers": [
                      {
                        "visibility": "off"
                      }
                    ]
                  },
                  {
                    "featureType": "poi.park",
                    "elementType": "labels.text.fill",
                    "stylers": [
                      {
                        "color": "#9e9e9e"
                      }
                    ]
                  },
                  {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [
                      {
                        "color": "#ffffff"
                      }
                    ]
                  },
                  {
                    "featureType": "road.arterial",
                    "elementType": "labels",
                    "stylers": [
                      {
                        "visibility": "off"
                      }
                    ]
                  },
                  {
                    "featureType": "road.arterial",
                    "elementType": "labels.text.fill",
                    "stylers": [
                      {
                        "color": "#757575"
                      }
                    ]
                  },
                  {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [
                      {
                        "color": "#dadada"
                      }
                    ]
                  },
                  {
                    "featureType": "road.highway",
                    "elementType": "labels",
                    "stylers": [
                      {
                        "visibility": "off"
                      }
                    ]
                  },
                  {
                    "featureType": "road.highway",
                    "elementType": "labels.text.fill",
                    "stylers": [
                      {
                        "color": "#616161"
                      }
                    ]
                  },
                  {
                    "featureType": "road.local",
                    "stylers": [
                      {
                        "visibility": "off"
                      }
                    ]
                  },
                  {
                    "featureType": "road.local",
                    "elementType": "labels",
                    "stylers": [
                      {
                        "visibility": "off"
                      }
                    ]
                  },
                  {
                    "featureType": "road.local",
                    "elementType": "labels.text.fill",
                    "stylers": [
                      {
                        "color": "#9e9e9e"
                      }
                    ]
                  },
                  {
                    "featureType": "transit.line",
                    "elementType": "geometry",
                    "stylers": [
                      {
                        "color": "#e5e5e5"
                      }
                    ]
                  },
                  {
                    "featureType": "transit.station",
                    "elementType": "geometry",
                    "stylers": [
                      {
                        "color": "#eeeeee"
                      }
                    ]
                  },
                  {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                      {
                        "color": "#c9c9c9"
                      }
                    ]
                  },
                  {
                    "featureType": "water",
                    "elementType": "labels.text.fill",
                    "stylers": [
                      {
                        "color": "#9e9e9e"
                      }
                    ]
                  }
                ]
            });
            
            var infowindow = new google.maps.InfoWindow({});
    
        	var marker, i;
        
        	for (i = 0; i < locations.length; i++) {
        		marker = new google.maps.Marker({
        			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        			map: map
        		});
        		
        		var loc = new google.maps.LatLng(locations[i][1], locations[i][2]);
        		bounds.extend(loc);
        
        		google.maps.event.addListener(marker, 'click', (function (marker, i) {
        		    marker.setIcon('/hrm/skin/img/roya-map-icon.png');
        			return function () {
        				infowindow.setContent(locations[i][0]);
        				infowindow.open(map, marker);
        			}
        		})(marker, i));
        		
        		markers.push(marker);
        	}
    
        	map.fitBounds(bounds);
            map.panToBounds(bounds);
            <?php
            }
            else
            {
            ?>
            var map = new google.maps.Map(document.getElementById('map'), {
    		zoom: 8,
    		center: new google.maps.LatLng(24.397975914716742,54.35064764448177),
    		styles: [
              {
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#f5f5f5"
                  }
                ]
              },
              {
                "elementType": "labels.icon",
                "stylers": [
                  {
                    "visibility": "off"
                  }
                ]
              },
              {
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#616161"
                  }
                ]
              },
              {
                "elementType": "labels.text.stroke",
                "stylers": [
                  {
                    "color": "#f5f5f5"
                  }
                ]
              },
              {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#bdbdbd"
                  }
                ]
              },
              {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#eeeeee"
                  }
                ]
              },
              {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#757575"
                  }
                ]
              },
              {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#e5e5e5"
                  }
                ]
              },
              {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#9e9e9e"
                  }
                ]
              },
              {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#ffffff"
                  }
                ]
              },
              {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#757575"
                  }
                ]
              },
              {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#dadada"
                  }
                ]
              },
              {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#616161"
                  }
                ]
              },
              {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#9e9e9e"
                  }
                ]
              },
              {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#e5e5e5"
                  }
                ]
              },
              {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#eeeeee"
                  }
                ]
              },
              {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#c9c9c9"
                  }
                ]
              },
              {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#9e9e9e"
                  }
                ]
              }
            ]
    	});
    
    	var infowindow = new google.maps.InfoWindow({});
    
    	var marker, i;
    
    	for (i = 0; i < locations.length; i++) {
    		marker = new google.maps.Marker({
    			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
    			map: map
    		});
    
    		google.maps.event.addListener(marker, 'click', (function (marker, i) {
    		    marker.setIcon('/hrm/skin/img/roya-map-icon.png');
    			return function () {
    				infowindow.setContent(locations[i][0]);
    				infowindow.open(map, marker);
    			}
    		})(marker, i));
    	}
        <?php
        }
        ?>
        

    
      }
        
        var generateIconCache = {};
        
        function generateIcon(number, callback) {
            /*
          if (generateIconCache[number] !== undefined) {
            callback(generateIconCache[number]);
          }
        
          var fontSize = 16,
            imageWidth = imageHeight = 35;
        
          if (number >= 1000) {
            fontSize = 10;
            imageWidth = imageHeight = 55;
          } else if (number < 1000 && number > 100) {
            fontSize = 14;
            imageWidth = imageHeight = 45;
          }
        
          var svg = d3.select(document.createElement('div')).append('svg')
            .attr('viewBox', '0 0 54.4 54.4')
            .append('g')
        
          var circles = svg.append('circle')
            .attr('cx', '27.2')
            .attr('cy', '27.2')
            .attr('r', '21.2')
            .style('fill', '#f18131');
        
          var path = svg.append('path')
            .attr('d', 'M27.2,0C12.2,0,0,12.2,0,27.2s12.2,27.2,27.2,27.2s27.2-12.2,27.2-27.2S42.2,0,27.2,0z M6,27.2 C6,15.5,15.5,6,27.2,6s21.2,9.5,21.2,21.2c0,11.7-9.5,21.2-21.2,21.2S6,38.9,6,27.2z')
            .attr('fill', '#FFFFFF');
        
          var text = svg.append('text')
            .attr('dx', 27)
            .attr('dy', 32)
            .attr('text-anchor', 'middle')
            .attr('style', 'font-size:' + fontSize + 'px; fill: #FFFFFF; font-family: Arial, Verdana; font-weight: bold')
            .text(number);
        
          var svgNode = svg.node().parentNode.cloneNode(true),
            image = new Image();
        
          d3.select(svgNode).select('clippath').remove();
        
          var xmlSource = (new XMLSerializer()).serializeToString(svgNode);
        
          image.onload = (function(imageWidth, imageHeight) {
            var canvas = document.createElement('canvas'),
              context = canvas.getContext('2d'),
              dataURL;
        
            d3.select(canvas)
              .attr('width', imageWidth)
              .attr('height', imageHeight);
        
            context.drawImage(image, 0, 0, imageWidth, imageHeight);
        
            dataURL = canvas.toDataURL();
            generateIconCache[number] = dataURL;
        
            callback(dataURL);
          }).bind(this, imageWidth, imageHeight);
        
          image.src = 'data:image/svg+xml;base64,' + btoa(encodeURIComponent(xmlSource).replace(/%([0-9A-F]{2})/g, function(match, p1) {
            return String.fromCharCode('0x' + p1);
          }));
          */
        }
        
        
            function geoselecteddata(name)
              {
                  var array = []
                    <?php
                    $slno=0;
                    foreach($map_data as $key=>$row)
                    { 
                    ?>
                        array['<?php echo $row['full_name']; ?>'] = <?php echo $key; ?>;
                    <?php
                    $slno++;
                    }
                    ?>
                  loadcountry(array[name]);
              }
        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCHTdvL6NsMY2oDLXRy-P9iKk78j-fxDo&callback=initMap"></script>
      
      

    </div>
  </div>
</div>