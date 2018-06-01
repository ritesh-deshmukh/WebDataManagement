<!--Project 4-->
<!--Name: Ritesh Deshmukh-->
<?php
session_start();
?>
<!--// API key = myEBAYID-->
<!--// Tracking ID = trkID-->
<html>
<head><title>Buy Products</title>
<?php
    function alert($message) {
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
?>
</head>
<br>
<!--<center><b>Buy Products</b></center></br>-->
<!--<form action="buy.php" method="GET" >-->
<!--    <input type="hidden" name="clear" value="1"/>-->
<!--    <center><input type="submit" value="Empty Basket"/></center>-->
<!--</form>-->

<form action="buy.php" method="GET">
    <fieldset style="border: solid">
        <legend><h2>Buy Products</h2></legend>
    <center>
                <?php
                error_reporting(E_ALL);
                ini_set('display_errors','On');
                header('Content-Type: text');
                // Display a dropdown with the list of categories
                $xmlstrCatTree = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=myEBAYID&visitorUserAgent&visitorIPAddress&trackingId=trkID&categoryId=72&showAllDescendants=true');
                $xml1 = new SimpleXMLElement($xmlstrCatTree);
                // print $xmlstrCatTree;
                // print 'test';
                foreach ($xml1 -> category[0] -> categories as $cat1){
                    print '<label><b>Category Menu: &nbsp;</b></label>';
                    print '<select name = "category">';
                    foreach ($cat1 -> category as $cat2){
                        // alert("1");
                        print '<optgroup label="'.$cat2 -> name. '"></optgroup>';
                        foreach ($cat2 -> categories -> category as $cat3){
                            print '<option value="'.$cat3['id'].'">'.$cat3 -> name.'</option>';
                        }
                    }
                    print '</select>';
                }
                // Update basket
                if (isset($_GET["delete"])){
                    if (isset($_SESSION['basket'])){
                        // alert("1");
                        $fillData1 = $_SESSION['basket'];
                        // $id = $fillData1['id'];
                        $id = $_GET["delete"];
                        if (in_array($id,array_keys($fillData1))){
                            unset($fillData1["$id"]);
                            $_SESSION['basket'] = $fillData1;
                        }
                    }
                }if (isset($_GET['clear'])){/*alert("cleartest");*/unset($_SESSION['basket']);}
                ?>

        <label><b>&nbsp;Find Products:&nbsp;</b><input type="text" name="search"/></label>
                    <input type="submit" value="Search"/>

        <form action="buy.php" method="GET" >
            <input type="hidden" name="clear" value="1"/>
            <center><br><input type="submit" value="Empty Basket"/></center>
        </form>

    </center>
    </fieldset>

    <?php
    // Adding products to the basket
    print '<fieldset style="border: solid">';
    if (isset($_GET["buy"])){
        $id = $_GET["buy"];
        if (isset($_SESSION['basket'])){
            // alert("1");
            $fillData1 = $_SESSION['basket'];
        }else{
            // alert("1");
            $fillData1 = array();
        }

        if (isset($_SESSION['fill'])){$final = $_SESSION['fill'];
            if(!in_array($id, array_keys($fillData1))){$fillData1["$id"] = $final["$id"];}
        }

        print '<legend><b>Basket Contents</b></legend></br>';
        print '<table border = "1">';
        print '<tr><th>Image</th><th>Name</th><th>Price</th><th>Link</th><th>Action</th></tr>';
        $finalAmt = 0;
        foreach ($fillData1 as $id => $new_val1){
            $finalAmt = $finalAmt + floatval($new_val1["minPrice"]);
            print '<tr><td align="center" width="150px"><image src="' . $new_val1["image"] . '"></image></td><td align="center" width="250px"><i>' . $new_val1["name"] . '</i></td><td align="center" width="60px">$' . $new_val1["minPrice"] . '</td><td ><a href="' . $new_val1["productOffersURL"] . '">' . $new_val1["productOffersURL"] . '</a></td><td align="center" width="100px"><a href="buy.php?delete=' . $id . '">Delete</a></td></tr>';
        }print '</table>'; print '</br><center><b>Final Amount: $' . $finalAmt . '</b></center></br>';
        // $num = $finalAmt;
        $_SESSION['basket'] = $fillData1;
    }else{
        // alert("1");
        if (isset($_SESSION['basket'])){
            $fillData1 = $_SESSION['basket'];
            print 'Basket Contents';
            print '<table border = "1">';
            print '<tr><th>Image</th><th>Name</th><th>Price</th><th>Link</th><th>Action</th></tr>';
            $finalAmt = 0;
            foreach ($fillData1 as $id => $new_val1){
                $finalAmt = $finalAmt + floatval($new_val1["minPrice"]);
                print '<tr><td align="center" width="150px"><image src="' . $new_val1["image"] . '"></image></td><td align="center" width="250px"><i>' . $new_val1["name"] . '</i></td><td align="center" width="60px">$' . $new_val1["minPrice"] . '</td><td><a href="' . $new_val1["productOffersURL"] . '">' . $new_val1["productOffersURL"] . '</a></td><td align="center" width="100px"><a href="buy.php?delete=' . $id . '">Delete</a></td></tr>';
            }print '</table>'; print 'Final Amount: $' . $finalAmt . '</br>';
        }
    }
    print '</fieldset></br>';
    ?>

    <?php
    // Get product and display it using user input
    if ((isset($_GET['category']) && !empty($_GET['category'])) && (isset($_GET['search']) && !empty($_GET['search']))){
        $new_category = $_GET['category'];
        $new_search = $_GET['search'];
        $fillData = array();
        $xmlstrGenSearch = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=myEBAYID&visitorUserAgent&visitorIPAddress&trackingId=trkID&numItems=20&categoryId=' . $new_category . '&keyword=' . $new_search);
        $xml2 = new SimpleXMLElement($xmlstrGenSearch);
        if (isset($xml2)){
            print '<table border = "1">';
            print '<tr><th>Image</th><th>Name</th><th>Price</th><th>Description</th><th>Link</th></tr>';
            foreach ($xml2 -> categories -> category -> items -> product as $final){ $x = $final['id'];
                print '<tr><td align="center" width="150px"><a href="buy.php?buy=' . $x . '"><image src="' . $final -> images[0] -> image -> sourceURL . '"></image></a></td><td align="center" width="150px"><i>' . $final -> name . '</i></td><td align="center" width="60px">$' . $final -> minPrice . '</td><td align="center" width="800px">' . $final -> fullDescription . '</td><td><a href="' . $final -> productOffersURL . '">' . $final -> productOffersURL . '</a></td></tr>';
                $info = array("image" => (string)$final->images[0] -> image -> sourceURL, "name" => (string)$final -> name, "minPrice" => (string)$final -> minPrice, "fullDescription" => (string)$final -> fullDescription, "productOffersURL" => (string)$final -> productOffersURL, "productId" => (string)$x);
                $fillData["$x"] = $info;
            }print '</table>';
        }$_SESSION['fill'] = $fillData;
    }else{
        if (isset($_SESSION['fill'])){
            // alert("1");
            $final = $_SESSION['fill'];
            print '<table border = "1">';
            print '<tr><th>Image</th><th>Name</th><th>Price</th><th>Description</th><th>Link</th></tr>';
            foreach ($final as $new_val => $new_val1){
                print '<tr><td align="center" width="150px"><a href="buy.php?buy=' . $new_val1["productId"] . '"><image src="' . $new_val1["image"] . '"></image></a></td><td align="center" width="150px"><i>' . $new_val1["name"] . '</i></td><td align="center" width="60px">$' . $new_val1["minPrice"] . '</td><td align="center" width="800px">' . $new_val1["fullDescription"] . '</td><td><a href="' . $new_val1["productOffersURL"] . '">' . $new_val1["productOffersURL"] . '</a></td></tr>';
            }print '</table>';
        }
    }
    ?>
</form>
</body>
</html>
