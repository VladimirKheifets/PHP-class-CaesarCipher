<?
/*
	Demo PHP class CaesarCipher
	Version: 1.1
	Author: Vladimir Kheifets (vladimir.kheifets@online.de)	
	Copyright ©2021 Vladimir Kheifets All Rights Reserved
*/	
session_start();
//https://www.alto-booking.com/timeCalculator/
#######################################################################
include_once("index.inc.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Class CaesarCipher demo</title>
<link rel="stylesheet" href="css/CaesarCipher.css" >
<link rel="stylesheet" href="css/modal.css" >
<link rel="stylesheet" href="css/button_to_up.css" >
<script src="CompactDOM.min.js"></script>
<script src="CaesarCipher.js"></script>
</head>
<?
$demo2=explode("<br>",$_18)[1];
echo <<<HTML
<body>
<div class="content">
<div class="top">
<span class="FL"><span id="cookie">$CookieLink</span>&#9660;</span>
<span class="FR"><span id="la">$la_active</span>&#9660;</span>
</div>
<h1>$_1</h1>
<h2>$_2
<div><span id="demo2">$demo2</span>&#9656;</div>
</h2>
<p>$_3</p>
<form name="test" method="post">
<h2>
$_4: <select name="alphabet" id="alphabet">
$options
</select>
 $_5: <select name="key" id="key"> 
$options_key
</select>
</h2>
$sel_text
</form>
</p>
HTML;

include_once("ClassCaesarCipher.php");

$CaesarСipher = new CaesarCipher($alphabet_frequency);

//-----------------------------------------
$text = $test_text[$n_text];
$text_encode = $CaesarСipher->encode($text, $key) -> text;
$text_decode = $CaesarСipher->decode($text_encode, $key) -> text;
print_text($_6, $text_encode, $key);
print_text($_7, $text_decode);

//---------------------------------------------------------------------------

echo "<br><hr><b>$_8</b><br>";
$res = $CaesarСipher->BruteForceDecoding($text_encode);
$MaxRatingKey = $res -> MaxRatingKey;			
$MaxRating = $res -> MaxRating;
$decoded = $res -> decoded;
$keyRating = $res -> keyRating;
$rating = $res -> rating;
print_text($_9, $decoded[$MaxRatingKey], $res);
block_view_trigger(1, $_10);	
foreach ($rating as $item)
{	
	$repl["key"] = $key = $item[0];
	$repl["rating"] = $keyRating[$key];
	$text=$decoded[$key];
	print_text($_11,$text, $repl);
}
echo "</div>";
//---------------------------------------------------------------------------

echo "<br><hr><b>$_12</b><br>";
$res = $CaesarСipher->DecodingByCharacterFrequency($text_encode);

if($res->error == 2)
{
	echo $_13;
}
else
{

	$character = $res->MostFrequentlyCharacter;
	$indA = $res->MostFrequentlyCharacterInd;	
	$character = ord($character)==32?$_14:$character;	
	$s=DicReplaceV($_15, compact("character","indA"))."<br>";    
    $keyRating = $res -> keyRating;
	$MaxRatingKey = $res -> MaxRatingKey;
	$MaxRating = $res -> MaxRating;
	$decodedKeys = $res -> decodedKeys;	
	$alldecoded = $res -> decoded;
	$rating = $res -> rating;
	$i_decoded = $decodedKeys[$MaxRatingKey];
	$decoded = $alldecoded[$i_decoded];		
	print_text($s.$_16, $decoded[3], $res);	    
	block_view_trigger(2, $_10);
	foreach ($rating as $item) 
	{	
		if($item[1]>0)
		{		
			$repl["indA"]=$indA;
			$repl["key"] = $key=$item[0];
			$i=$decodedKeys[$key];
			$decoded = $alldecoded[$i];	
			$repl["decoded"] = $decoded[1];			
			$repl["character"] = ord($decoded[0])==32?$_14:$decoded[0];
			$repl["keyRating"] = $keyRating[$key];		
			print_text($_17, $decoded[3], $repl);
		}

	}
}
$Y=date("Y");
echo <<<HTML
</div>
<hr>
<div class="copy"><p>©</p>$Y Alto Booking</div>
</div>
</body>
</html>
HTML;
?>