<?php

/**
/ VIMEO VIDEO PARSER v.0.2
/ For https://www.webasyst.ru/store/plugin/shop/galleryplus/
*/

//VIDEO LINK EXAMPLE
//https://player.vimeo.com/video/XXXXXXXXX?autoplay=1&title=0&byline=0&portrait=0
//
//RESULT EXEMPLE
//https://i.vimeocdn.com/video/XXXXXXXXX.jpg?mw=96&mh=96

session_start();
$links='';
$json='';
$result='';
$codes_arr=array();

$m_w=96;
$m_h=96;

$p_w=750;
$p_h=422;

$p2_w=1280;
$p2_h=720;


if (isset($_POST['url'])){

	$m_w=(int)$_POST['m_w'];
	$m_h=(int)$_POST['m_h'];

	$p_w=(int)$_POST['p_w'];
	$p_h=(int)$_POST['p_h'];

	$p2_w=(int)$_POST['p2_w'];
	$p2_h=(int)$_POST['p2_h'];

	$_SESSION['m_w'] = $m_w;
	$_SESSION['m_h'] = $m_h;

	$_SESSION['p_w'] = $p_w;
	$_SESSION['p_h'] = $p_h;

	$_SESSION['p2_w'] = $p2_w;
	$_SESSION['p2_h'] = $p2_h;

	$url_arr = $_POST['url'];
	if (count($url_arr)>50){
			$result='<div class="w3-panel w3-red w3-margin"><h3>Ошибка</h3><p>Лимит 50 URL</p></div>';
			$codes_arr=array();	
	}

	foreach ($url_arr as $k=>$url){
		if (strpos($url, 'vimeo.com/video') !== false && filter_var($url, FILTER_VALIDATE_URL)){
			$f = file_get_contents($url);
			preg_match('/vimeocdn\.com\/video\/([0-9]+)/i', $f, $out);
			$codes_arr[]=$out[1];
		} else {
			$result='<div class="w3-panel w3-red w3-margin"><h3>Ошибка</h3><p>Неверный URL</p></div>';
			$codes_arr=array();
			break;	
		}
	}

}

if (count($codes_arr)>0){

if (isset($_SESSION['m_w'])){
	$m_w=(int)$_SESSION['m_w'];
	$m_h=(int)$_SESSION['m_h'];

	$p_w=(int)$_SESSION['p_w'];
	$p_h=(int)$_SESSION['p_h'];

	$p2_w=(int)$_SESSION['p2_w'];
	$p2_h=(int)$_SESSION['p2_h'];
}

$json .='[';
$n=0;
$cnt=count($codes_arr);
	foreach ($codes_arr as $key=>$id){
		$n++;
		$id=htmlentities($id, ENT_QUOTES, "UTF-8");
		
		$links.='<div class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin w3-padding"><h4 style="color:black">'.htmlentities($url_arr[$key], ENT_QUOTES, "UTF-8").'</h4> ';
		
		$links .= 'https://i.vimeocdn.com/video/'.$id.'.jpg?mw='.$m_w.'&mh='.$m_h.'&q=85<br>
		<br>
		https://i.vimeocdn.com/video/'.$id.'.jpg?mw='.$p_w.'&mh='.$p_h.'&q=70<br>
		<br>
		https://i.vimeocdn.com/video/'.$id.'.jpg?mw='.$p2_w.'&mh='.$p2_h.'&q=70</div>';
		
		$json .= '["'.$url_arr[$key].'","https://i.vimeocdn.com/video/'.$id.'.jpg?mw='.$m_w.'&mh='.$m_h.'&q=85","https://i.vimeocdn.com/video/'.$id.'.jpg?mw='.$p_w.'&mh='.$p_h.'&q=70","https://i.vimeocdn.com/video/'.$id.'.jpg?mw='.$p2_w.'&mh='.$p2_h.'&q=70"]';
		if($n<$cnt) {
			$json .= ',';
		}		
	}

$json .=']';
}

if (!empty($links)){
	$result.='<div class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin w3-padding"><h2>Json (for Gallery+):</h2> '.htmlentities($json, ENT_QUOTES, "UTF-8").'</div>';
	$result.='<hr>';
	$result.=$links;
}

if (isset($_SESSION['m_w'])){
	$m_w=(int)$_SESSION['m_w'];
	$m_h=(int)$_SESSION['m_h'];

	$p_w=(int)$_SESSION['p_w'];
	$p_h=(int)$_SESSION['p_h'];

	$p2_w=(int)$_SESSION['p2_w'];
	$p2_h=(int)$_SESSION['p2_h'];
}

print '<!DOCTYPE html>
<html>
<title> VIMEO LINKS </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<body>

<form action="index.php" method="post" class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin">
<h2 class="w3-center"><a href="/vimeo"><i class="w3-xxlarge fa fa-vimeo"></i> VIMEO LINK PARSER</a></h2>
<div class="w3-center">
Размеры: миниатюр: <input style="width:40px;display:inline-block" value="'.$m_w.'" class="w3-input w3-border" name="m_w" type="text" placeholder="Ширина">
<input style="width:40px;display:inline-block" value="'.$m_h.'" class="w3-input w3-border" name="m_h" type="text" placeholder="Высота">

превью: <input style="width:60px;display:inline-block" value="'.$p_w.'" class="w3-input w3-border" name="p_w" type="text" placeholder="Ширина">
<input style="width:60px;display:inline-block" value="'.$p_h.'" class="w3-input w3-border" name="p_h" type="text" placeholder="Высота">

превью 2x: <input style="width:60px;display:inline-block" value="'.$p2_w.'" class="w3-input w3-border" name="p2_w" type="text" placeholder="Ширина">
<input style="width:60px;display:inline-block" value="'.$p2_h.'" class="w3-input w3-border" name="p2_h" type="text" placeholder="Высота">

</div>

<div id="links">
	<div id="link">
		<div class="w3-row w3-section">
		  <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-external-link"></i></div>
		    <div class="w3-rest">
		      <input class="w3-input w3-border" name="url[]" type="text" placeholder="Vimeo video URL">
		    </div>
		</div>
	</div>

</div>


<p class="w3-center">
<button id="addLink" class="w3-button w3-section w3-green w3-ripple"> ➕ Добавить ссылку </button><br>
<button class="w3-button w3-section w3-blue w3-ripple"> ☑️ Получить </button>
</p>
</form>

'.$result.'

<script>
$(function() {

    $("#addLink").click(function(e){
    	e.preventDefault();
    	console.log($("#link").html());
	$("#links").append($("#link").html());    

    });
        
});
</script>

<div class="w3-panel w3-pale-green w3-center">
  <p>Вы можете использовать этот сервис, что бы получить ссылки на превью, видео роликов размещенных на видеохостинге Vimeo.com<br> для использования их в плагине <a target="_blank" href="https://www.webasyst.ru/store/plugin/shop/galleryplus/">Галерея+</a>, либо, по своему усмотрению.<br>
  v.0.2
  </p>
</div> 

</body>
</html>';
