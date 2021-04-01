<?
/*
	PHP class CaesarCipher
	Version: 1.2, 2021-04-01
	Author: Vladimir Kheifets (vladimir.kheifets@online.de)	
	Copyright (c) 2021 Vladimir Kheifets All Rights Reserved
*/

class CaesarCipher {
	
	protected $alphabet;
	protected $alphabet_ind;
	protected $alphabet_count;
	protected $frequency;
	protected $alphabet_count_s;
	protected $most_frequently_used;
	protected $most_frequently_used_ind;
	protected $most_frequently_used_count;
	protected $min_character_frequency;	

	function __construct($alphabet_frequency=null, $min_frequency=3) {
		if($alphabet_frequency)
		{			        
			foreach($alphabet_frequency as $k=>$v) $alphabet_frequency_2c[]=[$k,$v];			
			$frequency = $c1 = array_column($alphabet_frequency_2c, 1);		
			$alphabet = array_column($alphabet_frequency_2c, 0);
			$min_character_frequency = (max($frequency) - min($frequency)) * 0.2;		
			$alphabet_count = count($alphabet);			
			$alphabet_count_s = $alphabet_count - 1;
			array_multisort($c1, SORT_DESC, $alphabet_frequency_2c);
			$most_frequently_used = [];						
			foreach ($alphabet_frequency_2c as $s) 
			{
				if($s[1]>$min_frequency) $most_frequently_used[] = $s[0];					
			}				
			$this->alphabet = $alphabet;
			$this->frequency = $frequency;
			$this->alphabet_ind = array_flip($alphabet);
			$this->alphabet_count = $alphabet_count;
			$this->alphabet_count_s = $alphabet_count_s;
			$this->most_frequently_used = $most_frequently_used;
			$this->most_frequently_used_ind = array_flip($most_frequently_used);
			$this->most_frequently_used_count = count($most_frequently_used);
			$this->min_character_frequency = $min_character_frequency;
		}		
	}	
	//-------------------------------------------------
	public function encode($inp_text, $key){
		
		if($error = self::check_input($inp_text, $key) > 0)
		{
			return (object)
			[
				"error" => $error,					 	   
			];
		}
		$alphabet=$this->alphabet;
		$alphabet_ind=$this->alphabet_ind;		
		$alphabet_count = $this->alphabet_count;
		$alphabet_count_s = $this->alphabet_count_s;
		$out_text="";
		$buf = preg_split('//u', $inp_text, null, PREG_SPLIT_NO_EMPTY);
		foreach ($buf as $symbol) {	
			$ind=$alphabet_ind[$symbol];
			$ind_c = $ind + $key;		
			if($ind_c < 0) 
				$ind_c += $alphabet_count;
			else if($ind_c > $alphabet_count_s)
				$ind_c -= $alphabet_count; 	
			$out_text.= $alphabet[$ind_c];
		}		
		return (object)
		[
			"error" => 0,
			"text" => $out_text				 	   
		]; 		
	}
//-------------------------------------------------
	public function decode($inp_text, $key){		
		if($error = self::check_input($inp_text, $key) > 0)
		{
			return (object)
			[
				"error" => $error,					 	   
			];
		}
		$key = -$key;
		$alphabet=$this->alphabet;
		$alphabet_ind=$this->alphabet_ind;	
		$frequency=$this->frequency;
		$min_character_frequency= $this->min_character_frequency;
		$most_frequently_used = $this->most_frequently_used;						
		$alphabet_count = $this->alphabet_count;
		$alphabet_count_s = $this->alphabet_count_s;
		$most_frequently_used_ind = $this->most_frequently_used_ind;
		$most_frequently_used_count = $this->most_frequently_used_count;
		$len_inp_text=mb_strlen($inp_text);	
		$out_text="";
		$buf = preg_split('//u', $inp_text, null, PREG_SPLIT_NO_EMPTY);			
		$rating=0;
		foreach($buf as $symbol)
		{
			$ind=$alphabet_ind[$symbol];
			$ind_k = $ind + $key;			
			if($ind_k < 0) 
				$ind_k +=$alphabet_count; 
			else if($ind_k > $alphabet_count_s) 
				$ind_k +=-$alphabet_count; 			
			$character = $alphabet[$ind_k];
			$i=$alphabet_ind[$character];
			if($frequency[$i] > $min_character_frequency AND $character!=" ")
			{	
				$k = 1 + ($most_frequently_used_count - $most_frequently_used_ind[$character])
				/($most_frequently_used_count - 1);
				//echo "$character $k ".$frequency[$i]." ".round($frequency[$i] * $k, 2)."<br>"; 				
			 	$rating += round($frequency[$i] * $k, 3);	
			}
			$out_text .= $character;			
		}
		//echo "<br>$rating<hr>";				
		return (object)
		[
			"error" => 0,
			"rating" => $rating,
			"text"	=> $out_text		 	   
		];
	}

	//--------------------------------------	
	public function  BruteForceDecoding($inp_text){	
		$alphabet_count = $this->alphabet_count;
		$decoded=[];
		for($key=1; $key< $alphabet_count;$key++)
		{	
			$res = self::decode($inp_text, $key);
			$decoded[$key] = $res->text;
			$rating[] = [$key, $keyRating[$key] = $res->rating];										
		}
		$ratingC1 = array_column($rating, 1);		
		array_multisort($ratingC1, SORT_DESC, $rating);		
		$MaxRatingKey = $rating[0][0];
		$MaxRating = $rating[0][1];		
		return (object)
		[
			"keyRating" => $keyRating,
			"MaxRatingKey" => $MaxRatingKey, 
			"MaxRating" => $MaxRating,
			"rating" => $rating,
			"decoded"	=> $decoded			
		];
			
	}
	//--------------------------------------	
	public function  DecodingByCharacterFrequency($inp_text, $MaxNumberDecoding=null){		
		if(empty($inp_text))
		return (object)
		[
			"error" => 1			 	   
		];
		$buf = preg_split('//u', $inp_text, null, PREG_SPLIT_NO_EMPTY);		
		$CharacterFrequency = self::GetCharacterFrequency($buf);		
		if($CharacterFrequency -> error)
		{
			return (object)
			[
			   "error" => 2			 	   
			];		
		}	

		$alphabet = $this->alphabet;
		$alphabet_ind = $this->alphabet_ind;
		$most_frequently_used = $this -> most_frequently_used;
		$max_number_mfu =  count($most_frequently_used);
		if
		(
			$MaxNumberDecoding > 0 
			AND 
			$MaxNumberDecoding < $max_number_mfu
		) 
		$max_number_mfu = $MaxNumberDecoding;			
		$MostFrequencyCharacter = $CharacterFrequency-> MostFrequentlyCharacter;		
		$iAmfuT = $CharacterFrequency -> MostFrequentlyCharacterInd;				
		//------------------------------------------------		
		$decoded=[];
		$decodedKeys=[];
		for ($i=0; $i < $max_number_mfu; $i++) { 	
			$CharacterMFU = $most_frequently_used[$i];
			$iAmfu = $alphabet_ind[$CharacterMFU];
			$key = $iAmfuT - $iAmfu;
			$decoded[$i][0] = $CharacterMFU;
			$decoded[$i][1] = $iAmfu;
			$decoded[$i][2] = $key;
			$res = self::decode($inp_text, $key);			
			$decoded[$i][3] = $res->text;
			$rating[] = [$key, $keyRating[$key] = $res->rating];
			$decodedKeys[$key]=$i;
		}
		$ratingC1 = array_column($rating, 1);		
		array_multisort($ratingC1, SORT_DESC, $rating);		
		$MaxRatingKey = $rating[0][0];
		$MaxRating = $rating[0][1];	
		return (object)
		[
			"error" => 0,		   
			"MostFrequentlyCharacter" => $MostFrequencyCharacter,
			"MostFrequentlyCharacterInd" => $iAmfuT,
			"keyRating" => $keyRating,
			"MaxRatingKey" => $MaxRatingKey, 
			"MaxRating" => $MaxRating,
			"decodedKeys" => $decodedKeys,
			"decoded" => $decoded,
			"rating" => $rating		   
		];	 
	}
	//-------------------------------------------------
	public function  GetCharacterFrequency($buf, $inp_alphabet=null, $decimal=2, $sort_col=null){
		if(empty($buf))
		return (object)
		[
			"error" => 1			 	   
		];
		if(!is_array($buf))
			$buf = preg_split('//u', $buf, null, PREG_SPLIT_NO_EMPTY);
		if($sort_col)
		{
			$sort_col=$sort_col-1;
			$sort = $sort_col==0?SORT_ASC:SORT_DESC;			
		}
		else
		{
			$sort=SORT_DESC;
			$sort_col=1;
		}			
		$alphabet = $this->alphabet;
		$alphabet_ind = $this->alphabet_ind;	
		$uniq_buf = array_unique($buf);
		$buf_count = count($buf);		
		$Frequency=[];
		if($buf_count==count($uniq_buf))
		{
			return (object)
			[
				"error" => 2				   
			];	
		}					
		foreach ($uniq_buf as $i => $vu) 
		{
			foreach($buf as $v) 
			{
				if($v===$vu) $Frequency[$i]++; 
			}
		}		
		foreach($Frequency as $i=>$vu) 
		{
			$character = $uniq_buf[$i];
			$f = round(($vu/$buf_count)*100, $decimal);					
			$CharacterFrequency[]=[$character, $f]; 
		}
		
		$CharacterFrequencyCol = array_column($CharacterFrequency, $sort_col);			
		array_multisort($CharacterFrequencyCol, $sort, $CharacterFrequency);			
		
		if($inp_alphabet)
		{
			if(is_string($inp_alphabet))
			{	
				$alphabet_arr = preg_split('//u', $inp_alphabet, null, PREG_SPLIT_NO_EMPTY);	
				$CharacterFrequencyC0 = array_flip(array_column($CharacterFrequency, 0));				
				foreach($alphabet_arr as $character)
				{					
					$i = $CharacterFrequencyC0[$character];										
					$alphabet_frequency[$character]=$CharacterFrequency[$i][1];					
				} 				
			}
			else
			{
				foreach($CharacterFrequency as $item)
				{
					$character = $item[0];
					$f = $item[1];
					$alphabet_frequency[$character]=$f;		
				}	

				
			}
			return (object)	
			[		   
				"error" => 0,
				"alphabet_frequency" => $alphabet_frequency							   
			];		
		}				
		
		$character = $CharacterFrequency[0][0];		
		return (object)
		[		   
			"error" => 0,
			"CharacterFrequency" => $CharacterFrequency,
			"MostFrequentlyCharacter" => $character,
			"MostFrequentlyCharacterInd" => $alphabet_ind[$character]		   
		];		
	}	
	//-------------------------------------------------
	protected function check_input($inp_text, $key){
		$error = 0;
		if(empty($inp_text)) $error++;			
		$a_key = abs($key);	
		$max_key = $this->alphabet_count_s;		
		if($a_key<1 OR $a_key > $max_key)  $error++;
		return $error;
	}
	//--------------------------------------------------
}
?>