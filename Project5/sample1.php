<h1 align="center">My Photo Album</h1>

<form enctype="multipart/form-data" action="album.php" method="post">
    <input type="hidden" name="MAX FILE SIZE" value="3000000" />
    Upload new Images : <input name="userFile" type="file" /><br/>
    <input type="submit" value="Upload File" />
</form>

<script type="text/javascript">
    function setImage(ImagePath){
        var image = document.getElementById("dropboxImg");
        image.src = ImagePath;
    }
</script>

<?php
error_reporting(E_ALL);
enable_implicit_flush();
set_time_limit(0);

require_once("DropboxClient.php");
$dropbox = new DropboxClient(array(
    'app_key' => "app_key",
    'app_secret' => "app_secret",
    'app_full_access' => false,
),'en');
initialize_dropbox($dropbox);

//Upload file to Dropbox
if (isset($_FILES['userFile'])){
    $file = $_FILES['userFile']['name'];
    $check = strtolower(end((explode(".", $file))));
    if($check != "jpg"){
        print "<h3 style=\"color: red\">Select a jpg file to upload.</h3>";
    }else{
        move_uploaded_file ($_FILES['userFile']['tmp_name'], getcwd() . "/" . $file );
        $dropbox->UploadFile($file);
        print "<h3 style=\"color: blue\">File uploaded successfully!!</h3>";
    }
}

//Delete file from Dropbox
$files = $dropbox->GetFiles("",false);
if(isset($_GET["delete"]))
{
    $key = $_GET["delete"];
    if(in_array($key, array_keys($files))){
        $file = $files[$key];
        $dropbox->Delete($file->path);
        print "<h3 style=\"color: red\">File deleted successfully!!</h3>";
    }
}

//Display image list and images
$files = $dropbox->GetFiles("",false);
if(!empty($files)){
    print "<table>";
    print "<tr>";

    $list = array_keys($files);

    print "<td width=\"450px\" valign=\"top\">";
    print "<div style=\"width:400px\">";
    print "<fieldset><legend>Dropbox Directory</legend>";
    print "<table valign=\"top\">";
    foreach($list as $item){
        $file = $files[$item];
        $path = $dropbox->GetLink($file,false);
        print "<tr valign=\"top\"><td>";
        print "<label style=\"color:blue\" onclick=\"setImage('" . $path ."');\"><u>" . $item . "</u></label></td>";
        print "<td><a href=\"album.php?delete=" . $item . "\"><button>Delete</button></a></td>";
        print "</tr>";
    }
    print "</table>";
    print "</fieldset></span>";
    print "</td>";
    print "<td align=\"center\" width=\"700px\">";
    print "<img id=\"dropboxImg\" alt=\"Select image from dropbox directory to view here..\" src=\"\" height=\"700px\" width=\"700px\"/></br>";	;
    print "</td>";
    print "</tr>";
    print "</table>";
}

function initialize_dropbox($dropbox){
    $access_token = load_token("access");
    if(!empty($access_token)) {
        $dropbox->SetAccessToken($access_token);
    }

    elseif(!empty($_GET['auth_callback']))
    {
        $request_token = load_token($_GET['oauth_token']);
        if(empty($request_token)) die('Request token not found!');
        $access_token = $dropbox->GetAccessToken($request_token);
        store_token($access_token, "access");
        delete_token($_GET['oauth_token']);
    }

    if(!$dropbox->IsAuthorized())
    {
        $return_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?auth_callback=1";
        $auth_url = $dropbox->BuildAuthorizeUrl($return_url);
        $request_token = $dropbox->GetRequestToken();
        store_token($request_token, $request_token['t']);
        die("Authentication required. <a href='$auth_url'>Click here.</a>");
    }
}

function store_token($token, $name)
{
    if(!file_put_contents("tokens/$name.token", serialize($token)))
        die('<br />Could not store token! <b>Make sure that the directory `tokens` exists and is writable!</b>');
}

function load_token($name)
{
    if(!file_exists("tokens/$name.token")) return null;
    return @unserialize(@file_get_contents("tokens/$name.token"));
}

function delete_token($name)
{
    @unlink("tokens/$name.token");
}

function enable_implicit_flush()
{
    @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
    for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
    ob_implicit_flush(1);
    echo "<!-- ".str_repeat(' ', 2000)." -->";
}
?>


