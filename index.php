<?php
require './api/Instagram.php';
use milan\api\instagram\Instagram;

$count = 4; // Set the number of pictures to display 
$userid = "1835595469"; // Instagram User ID   1835595469
$access_token = "1835595469.5b9e1e6.f9586fb65c614d95bf5349c69072928e"; // accessToken

$config = ["userID"=>$userid,"accessToken"=>$access_token];
$instagram = new Instagram($config);
if (isset($_GET["tag"]) && $_GET["tag"] !="" ) {
    $parse_data = $instagram->getTagMedia($_GET["tag"]);
} else {
    $parse_data = $instagram->getUserMedia($count);
}


$display_size = "standard_resolution";
$images = array();
if(!empty($parse_data)){
    foreach ($parse_data->data as $photo) {
        $img = $photo->images->{$display_size};
        $images[]['url']=$img->url;
    }
}
$output["src"] =$images; 
$output["duration"]=15;
$output["fade"]=3;
$output["scaling"]=FALSE;
$output["rotating"]=FALSE;
//$output["overlay"]="images/pattern.png";
$output["overlay"]="";
$f_output = json_encode($output);
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Instagram Wall</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.sublimeSlideshow.js"></script>
        <script type="text/javascript">
            
            $(function () {
                $.sublime_slideshow($.parseJSON('<?php echo $f_output; ?>'));

            });
        </script>
        <style type="text/css">

            /*Demo Styles*/
            p{ padding:0 30px 30px 30px; color:#fff; font:11pt "Helvetica Neue", "Helvetica", Arial, sans-serif; text-shadow: #000 0px 1px 0px; line-height:200%; }
            p a{ font-size:10pt; text-decoration:none; outline: none; color:#ddd; background:#222; border-top:1px solid #333; padding:5px 8px; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; -moz-box-shadow: 0px 1px 1px #000; -webkit-box-shadow: 0px 1px 1px #000; box-shadow: 0px 1px 1px #000; }
            p a:hover{ background-color:#427cb4; border-color:#5c94cb; color:#fff; }
            h3{ padding:30px 30px 20px 30px; }

            #content{ position:absolute; top:50px; left:50px; /*background:#111; background:rgba(0,0,0,0.65);*/ width:360px; text-align:left; }
            .stamp{ float: right; margin: 15px 30px 0 0;}

            footer {
                position: fixed;
                bottom:0px;
                width:100%;
                overflow: hidden;
                z-index: 1; 
                padding-top: 12px;
                background-color: rgba(0, 0, 0, 0.5);
            }			
        </style>
    </head>
    <body>
        <div id="content">
            <img src="images/logo.png"/>
        </div>
         <!--Twitter-->
        <footer>
            <iframe src="tweet.html" width="100%" height="100px" frameborder="0" scrolling="no"></iframe>
        </footer>
    </body>
</html>
