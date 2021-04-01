<?
$expires=time()+2592000;
$path="CaesarCipher";
if(isset($_POST['alphabet']))	
{
	extract($_POST);	
}
else if(!empty($_COOKIE["se"]))
{	
	$selected = (array)json_decode($_COOKIE["se"]);
	extract($selected);		
}
else if(!empty($_SESSION["se"]))
{		
	extract($_SESSION);		
}
if(empty($alphabet))
{
	$alphabet = "latina";
	$n_text = $key = 1;	
}
if(empty($_GET["la"])) 
{	if(empty($la))
	{
		$la=!empty($_SESSION["la"])?$_SESSION["la"]:"en";
	}
}
else
	$la = $_GET["la"];
$CookieLink = !empty($_COOKIE["se"])?"&#10004;Cookie":"Cookie";
saveSelected();
if(isset($_COOKIE["se"]))
{
	saveSelected(true,$expires, $path);
}
//---------------------------------------------------------------------------
extract(GetArrayFromFile("dictionary/$la.txt"),EXTR_PREFIX_ALL,"");
//---------------------------------------------------------------------------
$la_liste=file("dictionary/language.txt");
$href_suff="?la=";
$header_link = "";
foreach($la_liste as $line)
{
	$tmp = explode("~",trim($line));
	$lang=$hreflang=$tmp[0];
	$language[$lang] = $tmp[1];	
	if($tmp[2])	$hreflang.="-".$tmp[2];
	$href = $defhref.$href_suff.$lang;
	$header_link .= "<link rel=\"alternate\" hreflang=\"$hreflang\" href=\"$href\" />\n";	
}
$la_active=$language[$la];
//---------------------------------------------------------------------------
$alphabet_frequency = GetArrayFromFile("alphabets/alphabet_$alphabet.txt");
$options_key = "";
$max_keys = count($alphabet_frequency);
for ($i=1; $i < $max_keys; $i++) { 
	$selected = $i==$key?"selected":"";
	$options_key .= "<option $selected>$i</option>";
}
//---------------------------------------------------------------------------
$options_arr = file("alphabets/alphabets.txt");
$options = "";
foreach($options_arr as $value){
	$value=trim($value);
	$text=${"_$value"};
	$selected = $value==$alphabet?"selected":"";
	$options .= "<option value=\"$value\" $selected>$text</option>";
}
//---------------------------------------------------------------------------
$test_text = GetArrayFromFile("tests/test_$alphabet.txt"," ");
$sel_text = "";
foreach($test_text as $n => $text){
	$sel_text .= select_text($n, $n_text, $text);
}	
//---------------------------------------------------------------------------
extract(GetArrayFromFile("dictionary/$la.txt"),EXTR_PREFIX_ALL,"");
//---------------------------------------------------------------------------
$CookieLink = !empty($_COOKIE["se"])?"&#10004;Cookie":"Cookie";
//---------------------------------------------
if(isset($_GET["_la"]))
{
	extract($_POST);
	saveSelected();
	if(isset($_COOKIE["se"]))
	{
		saveSelected(true,$expires, $path);
	}
	echo "<div class=\"la-liste\">";
	foreach($language as $laS =>$name)
	{
		echo <<<HTML
		<span id="la_$laS">$name</span>
		HTML;
	}
	echo "</div>";	
	exit;
}
else if(isset($_GET["_cookie"]))
{
	$cookie_b=isset($_COOKIE["se"])?$_cookieN:$_cookieY;	
	echo <<<EOF
	<div class="set-cookie">
	$_cookie
	<div align="center"><p id="cookie_p">$cookie_b</p></div>	
	</div>
	EOF;	
	exit;
}
else if(isset($_GET["_cookie_p"]))
{		
	$link="Cookie";
	if(isset($_COOKIE["se"]))
	{
		saveSelected(true, 0, $path);
		echo $link;	
	}
	else
	{	
		extract($_POST);
		saveSelected(true, $expires, $path);
		echo "&#10004;$link";			
	}	
	exit;
}
else if(isset($_GET["_demo2"]))
{		
	include_once("Demo_GetCharacterFrequency.php");
	exit;
}	
//---------------------------------------------------
function GetArrayFromFile($file_name, $add_line=null){
	$buf=file($file_name);	
	if(!$add_line) $add_line = "<br>";
	foreach($buf as $line)
	{
		if(preg_match("/^\/{2}/",$line)) continue;
		if(preg_match("/^.+\~/",$line))
		{	
			$tmp = explode("~",$line);
			$out[$tmp[0]] = trim($tmp[1]);	
		}
		else
		{
			$out[$tmp[0]] .= $add_line.trim($line);
		}
	}
	return $out;
}
//---------------------------------------------------
function DicReplaceV($text, $repl){	
	if(is_array($repl) OR is_object($repl))
	{ 
	    $pattern="{\{(.*?)\}}";
	    preg_match_all($pattern,$text,$m, PREG_PATTERN_ORDER);
		$k=count($m[1]);	
		for ($i=0; $i<$k; $i++)
		{
			$a=$m[0][$i];
			$b=$m[1][$i];
			if(is_array($repl))	    
				$text = str_replace($a, $repl[$b], $text);
			else
				$text = str_replace($a, $repl->$b, $text);		
		}
	}
	else 
		$text = str_replace("{}",$repl,$text);
	return $text;
}
//---------------------------------------------------
function print_text($label, $text, $repl=null){	
if($repl) $label = DicReplaceV($label, $repl);	
echo <<<HTML
<p>
$label<br>
<textarea>$text</textarea>
</p>
HTML;
}
//---------------------------------------------------
function select_text($i, $i_check, $text){
$checked = $i==$i_check?"checked":"";	
return <<<HTML
<p>
<div><input name="n_text" id="n_text" type="radio" value="$i" $checked>$i</div>
<textarea>$text</textarea>
</p>
HTML;
}
//---------------------------------------------------
function block_view_trigger($i, $text, $repl=null){
if($repl) $text = DicReplaceV($text, $repl);	
echo <<<HTML
	<div id='vbl$i'>$text</div>
	<span id='sbl$i'>&#9660;</span>
	<div id='bl$i'>
	HTML;
}
//----------------------------------------------------------------
function saveSelected($inCookie=null,$expires=null, $path=null){	
	if($inCookie AND $expires==0) 
	{
		setcookie("se", "", time()-7200 ,$path);	
		unset ($_COOKIE["se"]);
		return;
	}
	global $la, $alphabet, $latina, $key, $n_text;			
	$selected=compact("la","alphabet", "key", "n_text");	
	if($inCookie)
	{
		$se=json_encode($selected);
		setcookie("se", $se, $expires,$path);
	}
	else
		$_SESSION["se"]=$selected;	
}
//----------------------------------------------------		
?>