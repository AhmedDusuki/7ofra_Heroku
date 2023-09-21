<!DOCTYPE html>
<head>
    <title>MAP</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin=""/>
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
       
    <style>
    h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 20pt;
        text-align: center;
    }
    #map {
        height: 600px;
        width: 800px;
        margin:  auto;
    }

    </style>
</head>

<body>
    <h3>7ofra</h3>
    <div id = "map"></div>
    <script>
        const SHOW_THRESHOLD = 3;
        const MIN_DISTANCE = 12;
        var mymap = L.map('map').setView([31.21,  29.91],15);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}',
        {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 19,
            minZoom: 4,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'sk.eyJ1IjoiM2Vsb28yaGFuZGFzYSIsImEiOiJja3RobjQwYWcwbjd6MnpwYXM4OGw4Y3pyIn0.IJHw3ZV7BZP-DQfIJThpPA'
        }).addTo(mymap);

        console.log(Object.getOwnPropertyNames(mymap));
        var currentLocation;
        var loadedGrids=new Array();
        var userInfo;


        async function getUserInfo(){
            var userinfo = await fetch('userinfo');
            userinfo.json().then(function(data){
                userInfo = data;
        });
        }
        getUserInfo();
        
        function getUserId(){ // can be cached    
            var userId = userInfo[0];
            console.log(userId);
            return userId;
        }

        /*function getUserNumber(userId){
            //userId = userInfo[0];
            var userNumber;
            fetch('/usernumber').then(userNumber => response.json());
            userNumber.json().then(function(data){
                userNumber = data;
            });
            console.log(userNumber);
            //var userNumber=userInfo[1];
            return userNumber;
        }*/

        function getUserHelp(){ // return id of help pin, default -1, must not be cached i.e.: must be fetched everytime the function is called.
            getUserInfo();
            var userHelp=userInfo[2];
            console.log(userInfo);
            return userHelp;
        }

        async function onLocationFound(e) {
            if (currentLocation==null){
                mymap.setView(e.latlng, 15);
                currentLocation = e.latlng;
                var curGrid = pointToGrid(currentLocation.lat,currentLocation.lng);
                await viewGrid(curGrid);
            }
            else{
                currentLocation = e.latlng;
            }
        }

        function onLocationError(e) {
            alert(e.message);
        }
        var response;
        var markerGroups = new Array();
        async function viewGrid(num) { // load from database grid records and draw pins on map
            if (loadedGrids.includes(num)){
            }
            else{
                await loadedGrids.push(num);
                var gridMarkers=L.layerGroup(); // can seperate to 3 layer groups
                response = await fetch('getPotholesGrid/'+num);
                response = await response.json().then(function(data){
                    response = data;
                    for (var i=0; i<response.length; i++){
                        gridMarkers.addLayer(drawPothole(response[i]));
                    }
                });
                response = await fetch('getRoadblocksGrid/'+num);
                response = await response.json().then(function(data){
                    response = data;
                    for (var i=0; i<response.length; i++){
                        gridMarkers.addLayer(drawRoadblock(response[i]));
                    }
                });
                response = await fetch('getHelpGrid/'+num);
                response = await response.json().then(function(data){
                    response = data;
                    for (var i=0; i<response.length; i++){
                        gridMarkers.addLayer(drawHelp(response[i], getUserNumber(response[i].user)));
                    }
                });
                mymap.addLayer(gridMarkers);
                markerGroups.push(num+','+Object.getOwnPropertyNames(mymap._layers).pop());
            }
        }
        var unconfirmedPothole = L.icon ({iconUrl: '{{url('img\/unconfirmedPothole.png')}}' , iconSize: [50, 50], iconAnchor: [25, 50], popupAnchor:  [25, 50] });
        var pothole = L.icon ({iconUrl: '{{url('img\/pothole.png')}}' , iconSize: [50, 50], iconAnchor: [25, 50], popupAnchor:  [25, 50] });
        function drawPothole(value){
            if ((JSON.parse(value["reports"]).length - JSON.parse(value["remove reports"]).length)>=SHOW_THRESHOLD){
                return L.marker([value.lat, value.lng], {icon: pothole});
            }
            else{
                return L.marker([value.lat, value.lng], {icon: unconfirmedPothole});
            }
        }

        var roadblock = L.icon ({iconUrl: '{{url('img\/roadblock.png')}}' , iconSize: [50, 50], iconAnchor: [25, 50], popupAnchor:  [25, 50] });
        function drawRoadblock(value){
            return L.marker([value.lat, value.lng], {icon: roadblock});
        }

        var help = L.icon ({iconUrl: '{{url('img\/help.png')}}' , iconSize: [50, 50], iconAnchor: [25, 50], popupAnchor:  [0, -45] });
        function drawHelp(value, phoneNum){
            return L.marker([value.lat, value.lng], {icon: help}).bindPopup(phoneNum);
        }

        async function watchMap(e) {
            var center = e.sourceTarget.getCenter();
            var curGrid = pointToGrid(center.lat,center.lng);
            await viewGrid(curGrid);
        }

        function deg2rad(degree){
            return degree * (Math.PI/180);
        }

        function latLngDistance(latitudeFrom, longitudeFrom, latitudeTo, longitudeTo, earthRadius = 6371000) {
            // convert from degrees to radians
            var latFrom = deg2rad(latitudeFrom);
            var lonFrom = deg2rad(longitudeFrom);
            var latTo = deg2rad(latitudeTo);
            var lonTo = deg2rad(longitudeTo);

            var lonDelta = lonTo - lonFrom;
            var a = Math.pow(Math.cos(latTo) * Math.sin(lonDelta), 2) +
            Math.pow(Math.cos(latFrom) * Math.sin(latTo) - Math.sin(latFrom) * Math.cos(latTo) * Math.cos(lonDelta), 2);
            var b = Math.sin(latFrom) * Math.sin(latTo) + Math.cos(latFrom) * Math.cos(latTo) * Math.cos(lonDelta);

            var angle = Math.atan2(Math.sqrt(a), b);
            return angle * earthRadius;
        }

        async function addPothole() { // add user shit
            var grid = pointToGrid(currentLocation.lat, currentLocation.lng);
            var potholes = await fetch('getPotholesGrid/'+grid);
            var closest = new Array(-1, MIN_DISTANCE);
            var latTo, lngTo, distance, j;
            potholes = await potholes.json().then(function(data){
                potholes = data;
                for (var i=0; i<potholes.length; i++){
                    latTo = parseFloat(potholes[i].lat);
                    lngTo = parseFloat(potholes[i].lng);
                    distance = latLngDistance(currentLocation.lat, currentLocation.lng, latTo, lngTo);
                    if (distance < MIN_DISTANCE && distance < closest[1]){
                        closest[0] = potholes[i].id;
                        closest[1] = distance;
                        j = i;
                    }
                }
                if (closest[0]!=-1){
                    var userArray = JSON.parse(potholes[j]["reports"]);
                    if (userArray.includes(getUserId())){
                        alert("You have already added to this pothole.");
                        return;
                    }
                    fetch('addPotholeExisisting/'+closest[0]+','+getUserId());
                }
                else{
                    fetch('addPothole/'+currentLocation.lat+','+currentLocation.lng+','+getUserId()+','+grid);

                }
                refreshView(grid);
            });
            

        }

        async function addRoadblock() { // add user shit
            var grid = pointToGrid(currentLocation.lat, currentLocation.lng);
            fetch('addRoadblock/'+currentLocation.lat+','+currentLocation.lng+','+getUserId()+','+grid);
            refreshView(grid);
        }

        async function refreshView(grid){
            for (var i=0; i<markerGroups.length; i++){
                if (parseInt(markerGroups[i].slice(0, markerGroups[i].search(',')))==grid){
                    mymap.eachLayer(function(layer){
                        if (layer._leaflet_id == parseInt(markerGroups[i].slice(markerGroups[i].search(',')+1))){
                            mymap.removeLayer(layer);
                            var j=-1;
                            for (var j=0; i<loadedGrids.length; j++){
                                if (loadedGrids[j]==grid){
                                    break;
                                }
                            }
                            loadedGrids.splice(j, 1);
                            markerGroups.splice(i, 1);
                            viewGrid(grid);
                            return;
                        }
                    });
                    return;
                }
            }
        }
        async function removePothole(){
            var grid = pointToGrid(currentLocation.lat, currentLocation.lng);
            var potholes = await fetch('getPotholesGrid/'+grid);
            var closest = new Array(-1, MIN_DISTANCE);
            var latTo, lngTo, distance, j;
            potholes = potholes.json().then(function(data){
                potholes = data;
                for (var i=0; i<potholes.length; i++){
                    latTo = parseFloat(potholes[i].lat);
                    lngTo = parseFloat(potholes[i].lng);
                    distance = latLngDistance(currentLocation.lat, currentLocation.lng, latTo, lngTo);
                    if (distance < MIN_DISTANCE && distance < closest[1]){
                        closest[0] = potholes[i].id;
                        closest[1] = distance;
                        j=i;
                    }
                }
                if (closest[0]!=-1){
                    var userRemoveArray = JSON.parse(potholes[j]["remove reports"]);
                    if (userRemoveArray.includes(getUserId())){
                        alert("You have already requested to remove this pothole.");
                        return;
                    }
                    fetch('removePothole/'+closest[0]+','+getUserId());
                }
                else{
                    alert("No nearby potholes");
                }
                refreshView(grid);
            });

        }

        async function removeRoadblock(){
            var grid = pointToGrid(currentLocation.lat, currentLocation.lng);
            var roadblocks = await fetch('getRoadblocksGrid/'+grid);
            var closest = new Array(-1, MIN_DISTANCE);
            var latTo, lngTo, distance;
            roadblocks = roadblocks.json().then(function(data){
                roadblocks = data;
                for (var i=0; i<roadblocks.length; i++){
                    latTo = parseFloat(roadblocks[i].lat);
                    lngTo = parseFloat(roadblocks[i].lng);
                    distance = latLngDistance(currentLocation.lat, currentLocation.lng, latTo, lngTo);
                    if (distance < MIN_DISTANCE && distance < closest[1]){
                        closest[0] = roadblocks[i].id;
                        closest[1] = roadblocks;
                    }
                }
                if (closest[0]!=-1){
                    fetch('removeRoadblock/'+closest[0]);
                }
                else{
                    alert("No nearby roadblocks");
                }
                refreshView(grid);
            });

        }

        async function addHelp(){
            if (getUserHelp() == -1){
                var grid = pointToGrid(currentLocation.lat, currentLocation.lng);
                fetch('addHelp/'+currentLocation.lat+','+currentLocation.lng+','+getUserId()+','+grid);
                refreshView(grid);
            }
            else{
                alert("You have already requested help.");
            }
        }

        async function removeHelp(){
            if (getUserHelp() == -1){
                alert("You have not requested help.");
            }
            else{
                var grid = pointToGrid(currentLocation.lat, currentLocation.lng);
                fetch('removeHelp/'+getUserHelp()+','+getUserId());
                refreshView(grid);
            }
        }

        
        var adjLat, adjLng;
        function roundNearest(num, near){
            return Math.round(num * (1/near)) / (1/near);
        }
        function pointToGrid(lat, lng){ // egypt 0.05 height, 0.1 width, 131 n per row
            adjLat = roundNearest(parseFloat(lat) - parseFloat("21.3"),0.05);
            adjLng = roundNearest(parseFloat(lng) - parseFloat("24.2"),0.1);
            return Math.floor(adjLng/0.1) + Math.floor(adjLat/0.05) * 131;
        }
        
        mymap.on('locationfound', onLocationFound);
        mymap.on('locationerror', onLocationError);
        mymap.on('moveend', watchMap);
        mymap.locate({watch: true, maxZoom: 19, enableHighAccuracy: true});
        //mymap.setView([currentLocation["lat"],currentLocation["lng"]],15);

    </script>

    <button type="button" , onclick="addPothole();">pothole</button>
    <button type="button" , onclick="removePothole();">remove pothole</button>
    <button type="button" , onclick="addRoadblock();">roadblock</button>
    <button type="button" , onclick="removeRoadblock();">remove roadblock</button>
    <button type="button" , onclick="addHelp();">help</button>
    <button type="button" , onclick="removeHelp();">remove help</button>
</body>
</html>