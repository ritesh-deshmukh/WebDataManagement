// Student Name: Ritesh Deshmukh

// API = myZillowAPIKey
var zwsid = "myZillowAPIKey";
var request = new XMLHttpRequest();
var map;
// alert(1).onload;
// var count = 0;
var markers = []; // for on click marker handling
var latlng = {lat: 32.75, lng: -97.13};
// var marker;
var x;
var res = []; // To store the address to display
var op = "";
var op1 = "";
var results = [];
var address;
var value;
var amount = []; // To store tha amount to display
var markers1 = []; // for typed address marker handling

function initialize () {
    // initially centered at (32.75, -97.13) with zoom level 17
    var geocoder = new google.maps.Geocoder();
    //var latlng = new google.maps.LatLng(32.75, -97.13);
    //var mapOptions = {
    //  zoom: 17,
    //  center: latlng
    //}
    map = new google.maps.Map(document.getElementById('map'), {zoom: 17, center: latlng, labelContent: 'hello'});

        function placeMarkerAndPanTo(latLng,map){
            // alert(1);
            //  address++;
            marker = new google.maps.Marker({
                position:latLng,
                map:map,
                draggable:true,
                title: 'hello',
                // label: results[0].formatted_address,
                // title: "<span>" + address1 + "</span>"

            });
            // infowindow.open(map,marker);
            markers.push(marker);
            map.panTo(latLng);
            // alert(markers.length);
    }
    //var infowindow = new google.maps.InfoWindow({
    //            content: "<span>"+ address1 +"</span>"
    //            });

    // Display info button click event
    document.getElementById('submit').addEventListener('click', function() {
        //res.push(results[0].formatted_address);
          geocodeAddress(geocoder, map);
        });

        // Mouse click over map
     x = google.maps.event.addListener(map, 'click', function(event) {

        if (markers.length<1){
            placeMarkerAndPanTo(event.latLng,map);
        } else {
            clearMarkers();
        }placeMarkerAndPanTo(event.latLng,map);

        geocoder.geocode({
            'latLng': event.latLng
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    address = results[0].formatted_address;
                    var city = "";
                    var state = "";
                    var zipcode = "";
                    request.onreadystatechange = displayResult;
                    request.open("GET","proxy.php?zws-id="+zwsid+"&address="+(results[0].formatted_address)+"&citystatezip="+city+"+"+state+"+"+zipcode);
                    res.push(results[0].formatted_address);
                    // amount.push(value);
                    request.withCredentials = "true";
                    request.send(null);
                    document.getElementById("addr").innerHTML = results[0].formatted_address;
                    // address1 = results[0].formatted_address;
                    // alert(results[0].formatted_address);
      }
    }
  });
} );
}

// to clear all the markers present on the map
function clearMarkers(){
    var len = markers.length;

    for (var i = 0; i < markers.length; i++){
        markers[i].setMap(null);
        // markers = [];
    }

    for (var j = 0; i < markers1.length; i++){
        markers1[i].setMap(null);
    }
}

// placing marker on screen for the address typed in the text fields
function geocodeAddress(geocoder, resultsMap) {
        // alert(1);
        var address = document.getElementById("address").value;
        var city = document.getElementById("city").value;
        var state = document.getElementById("state").value;
        var zipcode = document.getElementById("zipcode").value;
        var addr1 = address + " " + city + " " + state + " " + zipcode;
        //var addr1 = document.getElementById('addr').value;
        geocoder.geocode({'address': addr1}, function(results, status) {
          if (status === 'OK') {
              clearMarkers();
              // alert(1);
              // res.push(results[0].formatted_address);
              if (markers1.length<1) {
                  // alert(markers1.length);
                  resultsMap.setCenter(results[0].geometry.location);
                  res.push(results[0].formatted_address);
              }else {
                        // alert();
                     clearMarkers();
                    }resultsMap.setCenter(results[0].geometry.location);
              res.push(results[0].formatted_address);


            // resultsMap.setCenter(results[0].geometry.location);
            marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location,
                label: addr
            });
              markers1.push(marker);
              // res.push(results[0].formatted_address);
          } else {
            alert('Please enter a valid address');
          }
        });
      }
//function updateCounter(){
//        count++;
//        alert(count);
//    }

// Clear text fields
function clearRequest(){
    document.getElementById("address").value = "";
    document.getElementById("city").value = "";
    document.getElementById("state").value = "";
    document.getElementById("zipcode").value = "";
}

// Display the amount in $ for the property selected on the map
function displayResult () {
    if (request.readyState == 4) {
        var xml = request.responseXML.documentElement;
        value = xml.getElementsByTagName("zestimate")[0].getElementsByTagName("amount")[0].innerHTML;
	    document.getElementById("output").innerHTML = value;
        amount.push(value);

        // To display the address and amount in a list
        var j;
                for (j = 0; j<amount.length && j<res.length; j++){
                    op1 = op1 + "<p> >> " + res[j] + ". Amount =  $" + amount[j] + "</p>";
                }document.getElementById("amount").innerHTML =op1; res = []; amount = [];
    }
}

function sendRequest () {
    var address = document.getElementById("address").value;
    var city = document.getElementById("city").value;
    var state = document.getElementById("state").value;
    var zipcode = document.getElementById("zipcode").value;
    var addr = address + " " + city + " " + state + " " + zipcode;
    document.getElementById("addr").innerHTML = addr;
    request.onreadystatechange = displayResult;
    request.open("GET","proxy.php?zws-id="+zwsid+"&address="+address+"&citystatezip="+city+"+"+state+"+"+zipcode);
    request.withCredentials = "true";
    request.send(null);

}
