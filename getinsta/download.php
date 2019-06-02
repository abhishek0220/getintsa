<html><head><title>getinsta</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="img/logo2.jpg" type="image/png">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<style>
body,h1 {font-family: "Raleway", sans-serif}
body, html {height: 100%}
.btn {
  background-color: rgba(0,0,0,0.5);
  box-shadow: 4px 4px 20px rgba(255,255,255, 0.85);
  color: white;
  text-shadow: 0px 0px 10px black, 0px 0px 10px black;
  text-align: center;
  font-size: 24px;
  transition: 0.3s;
  border-radius: 0px;
  width:100%;
  box-sizing: border-box;
  padding: 1.5%;
}

.btn:hover {
  background-color:#00cc66;
  color: white;
}
.bgimg {
  text-shadow: 0px 0px 10px black, 0px 0px 10px black;
  background-image: url('img/garden2.jpg');
  min-height: 100%;
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
.text{
  color:#ffd633;
  padding-top:5%;
}

input {
background-color: rgba(0,0,0,0.5);
border: white;
color: white;
border-radius: 0px;
box-sizing: border-box;
font-size:25px;
padding: 1.5%;
}
input:required {
  box-shadow: 4px 4px 20px rgba(255,255,255, 0.85);
}
img{
  object-fit: contain;
}
</style>
</head>
<body class="bgimg w3-text-white">
  <div class="w3-display-topcenter " style="width:100%">
    
    <center>
    <h1 style=" font-size:7vw; color:white; text-shadow: 2px 2px 4px #ff80bf; border:black;"><b>getinsta</b></h1>

    <?php
    $img_dp="";
    $usr="";
    $img_arr=array();
    $media_arr=array();
    $url=trim($_POST["url"]);
    $st=strpos($url,"?") !== false;
    if($st!=false){
      $st=strpos($url,"?");
      $url=substr($url,0,$st);
    }
    $myfile = fopen("data_search", "a") or die("Unable to open file!");
    fwrite($myfile, $url);
    fwrite($myfile, "\n");
    fclose($myfile);
    $str_arr = explode ("/", $url);
    $con=0;
    $tem=strtolower(trim($str_arr[2]));
    $tem=substr($tem,strlen($tem)-13,strlen($tem));
    if($tem=="instagram.com"){
      if(strtolower(trim($str_arr[3]))=="p")
      {
        if(trim($str_arr[3])=="p"){
          $fi_url=rtrim($url,'/')."/?__a=1";
          $myfile = fopen($fi_url, "r") or die("Unable to open file!");
          $str="";
          while(!feof($myfile)) {
            $te=trim(fgets($myfile));
            $str=$str.$te;
          }
          fclose($myfile);
          $json = json_decode($str, true);
          if($json["graphql"]["shortcode_media"]["__typename"]=="GraphSidecar"){
            for ($i=0; $i <count($json["graphql"]["shortcode_media"]["edge_sidecar_to_children"]["edges"]) ; $i++) {
              $te=$json["graphql"]["shortcode_media"]["edge_sidecar_to_children"]["edges"][$i]["node"]["display_url"];
              array_push($img_arr,$te);
              if($json["graphql"]["shortcode_media"]["edge_sidecar_to_children"]["edges"][$i]["node"]["__typename"]=="GraphVideo"){
                $te=$json["graphql"]["shortcode_media"]["edge_sidecar_to_children"]["edges"][$i]["node"]["video_url"];
              }
              array_push($media_arr,$te);
            }
          }
          else{
            $te=$json["graphql"]["shortcode_media"]["display_url"];
            array_push($img_arr,$te);
            if($json["graphql"]["shortcode_media"]["__typename"]=="GraphVideo"){
              $te=$json["graphql"]["shortcode_media"]["video_url"];
            }
            array_push($media_arr,$te);
          }

        }
        else{
          $con=0;
        }
      }
      else{
        $fi_url=rtrim($url,'/')."/?__a=1";
        $file_headers=@get_headers($fi_url);
        if($file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
          $myfile = fopen($fi_url, "r");
          $str="";
          while(!feof($myfile)) {
            $te=trim(fgets($myfile));
            $str=$str.$te;
          }
          fclose($myfile);
          $json = json_decode($str, true);
          $te=$json["logging_page_id"];
          $te=explode ("_", $te);
          $uid=$te[1];
          if(strlen($uid)>0){
            $ur="https://i.instagram.com/api/v1/users/".$uid."/info/";
            $myfile = fopen($ur, "r") or die("Unable to open file!");
            $str="";
            while(!feof($myfile)) {
              $te=trim(fgets($myfile));
              $str=$str.$te;
            }
            fclose($myfile);
            $json = json_decode($str, true);
            $img_dp = $json["user"]["hd_profile_pic_url_info"]["url"];
            $usr=$json["user"]["full_name"];
            $con=1;
          }
        }
      }
    }
    else{
      $con=0;
    }
    if($con==0 && count($img_arr)>0){
      $con=2;
    }
    if($con==1){
      $a1= "<div class=\"w3-container\"><div class=\"w3-row\"><div class=\"w3-col s4 l4\">";
      $a2= "<p></p></div><div class=\"w3-col s4 l4\"><div class=\"w3-card\"><img src=";
      $a3= $img_dp." width=90% alt=\"Alps\">";
      $a4= "<a href=".$img_dp." target=\"_blank\"><div style=\"color:#00ff55; font-size:36px;\">";
      $a5= "<h5>Download</h5></div></a></div></div></div></div>";
      echo "<h5 style=\"font-size:4vw;\">Display Picture</h5>";
      echo $a1.$a2.$a3.$a4.$a5;
    }
    else{
      if($con==2){
        $row_o="<div class=\"w3-row\">";
        $row_c="</div>";
        $con_o="<div class=\"w3-container\">";
        $con_c="</div>";
        $col1="<div class=\"w3-col s4 l4\"><div class=\"w3-card\"><img src=";
        $col2=" width=90% alt=\"Alps\">";
        $col3="<a href=";
        $col4=" target=\"_blank\"><div style=\"color:#00ff55; font-size:36px;\"><h5>Download</h5></div></a></div></div>";
        echo "<h5 style=\"font-size:4vw;\">Post Media</h5>";
        $lit=0;
        $l=count($img_arr);
        if($l%3==0){
          $n=$l/3;
        }
        else{
          $n=$l/3;
          $n=$n+1;
        }
        $n=(int)$n;
        echo $con_o;
        for ($i=0; $i <$n ; $i++) {
          $k=3;
          if($i==$n-1){
            $k=$l-$lit;
          }
          echo $row_o;
          for ($j=0; $j <$k ; $j++) {
            $uri=$img_arr[$lit];
            $uri2=$media_arr[$lit];
            $lit=$lit+1;
            echo $col1.$uri.$col2.$col3.$uri2.$col4;
            // code...
          }
          echo $row_c;
        }
        echo $con_c;
      }
      else{
        echo "<h3 style=\"font-size:4vw; color:#ccff99\">No Insta Media Found :(</h3>";
      }
    }

    ?>
    </center>
  </div>
</body>
</html>
