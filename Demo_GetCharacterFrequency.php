<?
/*
	Demo PHP class CaesarCipher
	Version: 1.2, 2021-04-01
	Author: Vladimir Kheifets (vladimir.kheifets@online.de)	
	Copyright (c) 2021 Vladimir Kheifets All Rights Reserved
*/
echo <<<HTML
<div class="demo2" align="center">
<h1>$_1</h1>
<h2>$_2<br>
$_18<br>
</h2>
HTML;
$input=file("tests/latina.txt");
foreach($input as $item)
{
	if($item!="") $inp_text .= "$item ";
}	
$inp_text = mb_strtolower($inp_text);
$inp_text = preg_replace("/[^\p{L}\ ]/u", "", $inp_text);
$inp_len = strlen($inp_text);
$inp_text .= PHP_EOL;
$t=DicReplaceV($_19,$inp_len);
echo <<<HTML
<p>
$t<br>
<textarea id="inp_text">$inp_text</textarea>
HTML;

include_once("ClassCaesarCipher.php");

//https://www.alto-booking.com/demo/PHP_SU/CaesarCipher/Demo_GetCharacterFrequency.php
//$alphabet = "latina";
//include_once("alphabets/alphabet_$alphabet.php");
$CaesarСipher = new CaesarCipher();

$alpha = $CaesarСipher -> GetCharacterFrequency($inp_text, true, 3, 2)->alphabet_frequency;

echo '<textarea class="ffc">
<?
$alpha = $CaesarСipher -> GetCharacterFrequency($inp_text, true, 3, 2)-> alphabet_frequency;',PHP_EOL;
echo PHP_EOL;
echo 'foreach ($alpha as $key => $value) 
{
	echo "\'$key\' => ", number_format($value,3), PHP_EOL;
}',PHP_EOL;
echo '?>',PHP_EOL.PHP_EOL;
foreach ($alpha as $key => $value) 
{
	echo "'$key' => ",number_format($value,3),PHP_EOL;
}
echo '</textarea>';

$alphabet_str = " abcdefghijklmnopqrstuvwxyz";
$alpha = $CaesarСipher -> GetCharacterFrequency($inp_text, $alphabet_str, 3, 2) -> alphabet_frequency;

echo '<textarea class="ffc">';
echo '<?',PHP_EOL;
echo '$alphabet_str = " abcdefghijklmnopqrstuvwxyz";',PHP_EOL;
echo '$alpha = $CaesarСipher -> GetCharacterFrequency($inp_text, $alphabet_str, 3, 2) -> alphabet_frequency;',PHP_EOL;
echo PHP_EOL;
echo 'foreach ($alpha as $key => $value) 
{
	echo "\'$key\' => ", number_format($value,3), PHP_EOL;
}',PHP_EOL;
echo '?>',PHP_EOL.PHP_EOL;
foreach ($alpha as $key => $value) 
{
	echo "'$key' => ",number_format($value,3),PHP_EOL;
}
echo '</textarea>';
echo "</p><div>";
?>