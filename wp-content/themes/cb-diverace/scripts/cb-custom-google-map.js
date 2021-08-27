function initMap(map_marker) {
        // Find marker elements within map.
        //var $markers = $el.find('.marker');
        // Create gerenic map.
        var mapArgs = {
            zoom        : 3,
            mapTypeId   : google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map( document.getElementById("map"), mapArgs );

        // Add markers.
        map.markers = [];
        //$markers.each(function(){
            for(let i=0;i<map_marker.length;i++){
                initMarker( map_marker[i][0], map_marker[i][1], map, map_marker[i][2]);
            }            
        //});

        // Center map based on markers.
        centerMap( map );

        // Return map instance.
        return map;
    }

    function initMarker( lat, long, map, html ) {
        // Get position from marker.
        var lat = lat;
        var lng = long;

        
        var latLng = {
            lat: parseFloat( lat ),
            lng: parseFloat( lng )
        };

        // Create marker instance.
        const image ="https://diverace.chillybin.biz/wp-content/themes/cb-diverace/images/map_ship_icon.png";
        var marker = new google.maps.Marker({
            position : latLng,
            map: map,
            icon: image,        
        });

        // Append to reference for later use.
        map.markers.push( marker );

        // If marker contains HTML, add it to an infoWindow.      
        if( html ){
            // Create info window.
            var infowindow = new google.maps.InfoWindow({
                content: html
            });

            // Show info window when marker is clicked.
            google.maps.event.addListener(marker, 'click', function() {
                
                jQuery('.gm-ui-hover-effect').click();
                infowindow.open( map, marker );
            });

            /*alert("AA_"+map.markers.length);
            for (var i = 0; i < markers.length; i++) {
                var data = markers[i];

                (function (marker, data) {
                    google.maps.event.addListener(marker, "click", function (e) {
                        //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
                        infoWindow.setContent(html);
                        infowindow.open(map, marker);
                    });
                })(marker, data);
            }*/
        }
    }

    function centerMap( map ) {
        // Create map boundaries from all map markers.
        var bounds = new google.maps.LatLngBounds();
        map.markers.forEach(function( marker ){
            bounds.extend({
                lat: marker.position.lat(),
                lng: marker.position.lng()
            });
        });

        // Case: Single marker.
        if( map.markers.length == 1 ){
            map.setCenter( bounds.getCenter() );

        // Case: Multiple markers.
        } else{
            map.fitBounds( bounds );
        }
    }