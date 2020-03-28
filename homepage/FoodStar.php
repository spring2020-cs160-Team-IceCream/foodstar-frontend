<?php
echo <<<_END
<html>
<head><title>HW 3 Webpage</title></head>
<body>
	<form action="homepage.php" method="post" enctype="multipart/form-data">
<h1 style="text-align: center;">Food Star</h1>
<div>
<div style="float: left;"><form id="thisone" action="post_nrs_users.asp" method="post"><input height="60" src="https://image.flaticon.com/icons/png/512/56/56763.png" type="image" width="60" /></form></div>
<div style="float: right;"><form id="thistoo" action="post_nrs_users.asp" method="post"><input height="60" src="https://cdn3.iconfinder.com/data/icons/glyph/227/Button-Add-1-512.png" type="image" width="60" /></form></div>
</div>
<p align="Left">&nbsp;</p>
<p align="Left">&nbsp;</p>
<p align="Left">&nbsp;</p>
<p align="Left"><img style="display: block; margin-left: auto; margin-right: auto;" src="https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fcdn-image.foodandwine.com%2Fsites%2Fdefault%2Ffiles%2F1541088184%2Fyemenite-short-ribs-xl-MAG1218.jpg" alt="" width="244" height="244" /></p>
<p align="Center">Yemenite Short Ribs</p>
<p align="Center">$24.99</p>
<p align="Center"><img src="https://cdn3.iconfinder.com/data/icons/basicolor-votting-awards/24/198_star_favorite_vote_achievement-512.png" alt="" width="15" height="15" />&nbsp;4.5</p>
<p align="Center"><img src="https://previews.123rf.com/images/rehabicons/rehabicons1804/rehabicons180400048/100306579-map-pointer-icon-location-button.jpg" alt="" width="15" height="15" />0.7 miles away</p>
<p align="Center"><button>More Details</button></p>
<p align="Left">&nbsp;</p>
<p align="Left">&nbsp;</p>
<p align="Left"><img style="display: block; margin-left: auto; margin-right: auto;" src="https://40.media.tumblr.com/8ccc27daca338b6e827244e80bc5050c/tumblr_ngtx790sD91rrhpaao1_500.jpg" alt="" width="244" height="244" /></p>
<p align="Center">FreeStyle Sushi</p>
<p align="Center">$42.99</p>
<p align="Center"><img src="https://cdn3.iconfinder.com/data/icons/basicolor-votting-awards/24/198_star_favorite_vote_achievement-512.png" alt="" width="15" height="15" />&nbsp;4.7</p>
<p align="Center"><img src="https://previews.123rf.com/images/rehabicons/rehabicons1804/rehabicons180400048/100306579-map-pointer-icon-location-button.jpg" alt="" width="15" height="15" />0.7 miles away</p>
<p align="Center"><button>More Details</button></p>
<p align="Left">&nbsp;</p>
<div id="demo"></div>
 </body>
 <script>
 var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    var myObj = JSON.parse(this.responseText);
    document.getElementById("demo").innerHTML = myObj.name;
    console.log(myObj);
  }
};
xmlhttp.open("GET", "http://10.147.20.66/api", true);
xmlhttp.send();
 </script>
</html>
_END;

$json_url = "http://10.147.20.66/api";
$json = file_get_contents($json_url);
$data = json_decode($json, TRUE);
echo "<pre>";
print_r($data);
echo "</pre>";


?>
