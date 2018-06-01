<!--Name: Ritesh Deshmukh-->
<!--ID: 1001173911-->

<?php
require_once("DropboxClient.php");
$dropbox = new DropboxClient(array(
    'app_key' => "appKey",
    'app_secret' => "appSecret",
    'app_full_access' => false,
));
error_reporting(E_ALL);
set_time_limit(0);
require_once 'demo-lib.php';
demo_init();

$bearer_token = demo_token_load( "bearer" );
if ( $bearer_token ) {
    $dropbox->SetBearerToken( $bearer_token );
    print "<b style='margin-right: 2px'>loaded bearer token: " . json_encode( $bearer_token, JSON_PRETTY_PRINT ) . "</b>";
} elseif ( ! empty( $_GET['auth_redirect'] ) ) // are we coming from dropbox's auth page?
{
    // get & store bearer token
    $bearer_token = $dropbox->GetBearerToken( null, $return_url );
    demo_store_token( $bearer_token, "bearer" );
} elseif ( ! $dropbox->IsAuthorized() ) {
    // redirect user to Dropbox auth page
    $auth_url = $dropbox->BuildAuthorizeUrl( $return_url );
    die( "Authentication required. <a href='$auth_url'>Continue.</a>" );
}

//Image -> UPLOAD
if (isset($_FILES['myImage'])){
    $test_file = $_FILES['myImage']['name'];
    move_uploaded_file ($_FILES['myImage']['tmp_name'], getcwd() . "/" . $test_file );
    $dropbox->UploadFile($test_file);
    print "<br><b>Upload File....Success</b>";
}

//Image -> DELETE
$files = $dropbox->GetFiles("",false);
if(isset($_GET["delete"]))
{
    $key = $_GET["delete"];
    if(in_array($key, array_keys($files))){
        $test_file = $files[$key];
        $dropbox->Delete($test_file->path);
        print "<br><b>Delete File....Success</b>";
    }
}
?>
<div style="width:700px; margin:0 auto; padding: 0;">
    <form enctype="multipart/form-data" action="album.php" method="post">
        <table>
            <th>
                Image Upload<br>
            </th>
            <tr>
                <td>
                    <input type="file" name="myImage" accept="image"/><input type="submit" value="Upload File" />
                </td>
            </tr>
        </table>
    </form>
    <label><h3>Display Window</h3></label>
</div><div style="margin:0 auto; padding: 0;">

<?php

//Image -> DISPLAY
$files = $dropbox->GetFiles("",false);
if(!empty($files)){
    $list = array_keys($files);
    print "<div style='float: left; width: 30%; padding: 0;'>";
    print "<table  style='border: 1px solid; margin-top: 1px'>";
    foreach($list as $item){
        $test_file = $files[$item];
        $path = $dropbox->GetLink($test_file,false);
        print "<tr><td>";
        print "<b style='color:blue' onclick=img_call('" . $path ."');>" . $item . "</b></td>";
        print "<td><a href='album.php?delete=" . $item . "'><button>Delete</button></a></td>";
    }
    print "</tr></table></div>";
    print "<div style='width: 500px; height: 500px'>";
    print "<img id='Image_tile' src='' height='700px' width='700px'/>";
    print "</div>";
}
?>
</div>
<script>
    function img_call(img){
        var test_img = document.getElementById("Image_tile");
        test_img.src = img;
    }
</script>
