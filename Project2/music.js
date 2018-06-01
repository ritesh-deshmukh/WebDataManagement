// Name: Ritesh Deshmukh

var api_key = "myAPIKey";

function sendRequest_artistinfo () {
    var xhr = new XMLHttpRequest();
    var method = "artist.getinfo";
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr.open("GET", "proxy.php?method="+method+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr.setRequestHeader("Accept","application/json");
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            var json = JSON.parse(this.responseText);
            var str = JSON.stringify(json,undefined,2);
            var name = json.artist.name;
            var url = json.artist.url;
            // var info = json.artist.bio.summary;
            var info = json.artist.bio.content;
            var image = json.artist.image[3]['#text'];

            document.getElementById("artist_info").innerHTML = "<h3> Artist: " +"<i>"+ name + "</i>" + "</h3>" + "<p>URL: <a href='" + url + "'> " + url + "</a></p>" + "<p> Bio: " + info + "</p>" ;
            document.getElementById("artist_thumbnail").innerHTML = "<img src= '"+image+"'></img>";
        }
    };
    xhr.send(null);
    sendRequest_artistalbum();
}

function sendRequest_artistalbum () {
    var xhr = new XMLHttpRequest();
    var i;
    var method1 = "artist.gettopalbums";
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr.open("GET", "proxy.php?method="+method1+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr.setRequestHeader("Accept","application/json");
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            var json = JSON.parse(this.responseText);
            var im1 = json.topalbums.album;
            var str = JSON.stringify(json,undefined,2);
            var alb_img = "";
            for (i = 0; i<10; i++){
                // alert(i);
                alb_img = alb_img + "<ul><li>" +"<b>"+ "<i>" + json.topalbums.album[i].name + "</i>" + "</b><br>" +"<img src ='"+im1[i].image[2]['#text']+"' ></img></li></ul>";
            }
            document.getElementById("artist_albums").innerHTML = "<li><p>Top 10 albums</p>" + alb_img + "</li>";
        }
    };
    xhr.send(null);
}

function sendRequest_artistsimilar () {
    var xhr = new XMLHttpRequest();
    var i;
    var method2 = "artist.getsimilar";
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr.open("GET", "proxy.php?method="+method2+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr.setRequestHeader("Accept","application/json");
    var myarray = [];
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            var json = JSON.parse(this.responseText);
            var str = JSON.stringify(json,undefined,2);
            for (i = 0; i<=49; i++){
                myarray.push(json.similarartists.artist[i].name);
                document.getElementById("artist_similar").innerHTML = "<p> Similar Artists: " +  myarray + "</p>";
            }
        }
    };
    xhr.send(null);
}
