PHP class CaesarCipher

Version: 1.0, 2021-04-01

Author: Vladimir Kheifets (vladimir.kheifets@online.de)
Copyright (c) 2021 Vladimir Kheifets All Rights Reserved

The class is intended for the statistical analysis of texts in various languages,
encryption and decryption of texts using the "Caesar Cipher" algorithm.
 
Demo:
https://www.alto-booking.com/demo/github/CaesarCipher/

$CaesarСipher = new CaesarCipher (array | null $alphabet_frequency, int | null $min_frequency);

"alphabet_frequency", array, an array in which keys, string, alphabet character,
values, character frequency in% (statistical estimation of character recurrence frequency in texts)

For example, for the alphabet  of Latin language (obtained by the GetCharacterFrequency method, see section 5 below)

Array
(
    
    [] => 15.134
    
    [a] => 8.104
    
    [b] => 1.502
    
    [c] => 3.341
    
    [d] => 2.161
    
    [e] => 9.741
    
    [f] => 0.817
    
    [g] => 0.919
    
    [h] => 0.493
    
    [i] => 9.91
    
    [k] => 0.004
    
    [l] => 2.937
    
    [m] => 4.654
    
    [n] => 4.936
    
    [o] => 4.612
    
    [p] => 2.549
    
    [q] => 1.532
    
    [r] => 5.501
    
    [s] => 6.187
    
    [t] => 6.49
    
    [u] => 6.953
    
    [v] => 1.103
    
    [x] => 0.416
    
    [y] => 0.003
)

$min_frequency: min frequency of characters, value is applied
to identify the most commonly used symbols. By default, $min_frequency = 3;

Both parameters are optional for the GetCharacterFrequency method (see below in section 5).

For example:

$CaesarCipher = new CaesarCipher ();

$alphabet_frequency = $CaesarСipher -> GetCharacterFrequency ($inp_text, true, 3, 2) -> alphabet_frequency;


Methods of the CaesarCipher class.

1. The encode method

Encrypts text with the given key

$object = $CaesarСipher -> encode (string $inp_text, int $key);

$inp_text: string text to encrypt

$key: encryption key. value from 1 to max. the number of characters in the alphabet.

$object properties:

"error" is an int,
if $inp_text is not set, "error" => 1,
if the key is specified incorrectly, "error" => 2,
otherwise "error" => 0 returns:
"text" - string, ciphertext

2. The decode method

Decrypts the text with the given key and calculates the frequency rating of the decrypted text.

$object = $CaesarСipher -> decode (string $inp_text, int $key);

$inp_text: string text to decrypt
$key: encryption key

$object:
"error" is an int,
if $inp_text is not set, "error" => 1,
if the key is specified incorrectly, "error" => 2,
otherwise "error" => 0 returns:
"rating" - float, statistical rating of the decrypted text
"text" - string, decrypted text

3. The BruteForceDecoding method

Designed to crack the cipher by brute-force of all keys

$object = $CaesarСipher -> BruteForceDecoding (string $inp_text);

$inp_text: string text to decrypt

$object:
"keyRating" array, keys - int encryption key, values - float, decrypted text reting
"MaxRatingKey" int, the highest rated encryption key.
"MaxRating" float, maximum rating
"rating", array (array (int encryption key, float rating), ... (int encryption key, float rating))
"decoded" array, keys - int, encryption key, values - string, decrypted text

4. DecodingByCharacterFrequency method

Designed to crack a cipher with the calculation of encryption keys for particular characters
alphabet and cipher text.

$object = $CaesarCipher -> DecodingByCharacterFrequency (string $inp_text, int | null $MaxNumberDecoding);

$inp_text: string text to decrypt
$MaxNumberDecoding: int, allowed maximum number of decryption attempts,
by default, the maximum number of frequently used characters in the alphabet.
"error" is an int:
if $inp_text is not set, "error" => 1,

if all characters are unique in the ciphertext, "error" => 2,

otherwise "error" => 0 returns:

"MostFrequentlyCharacter", string, the most common character in an encrypted test.

"MostFrequentlyCharacterInd", int, the ordinal number of this character in the alphabet.

"keyRating", array, keys - int encryption key, values - float, decrypted text reting

"MaxRatingKey", int, the highest rated encryption key.

"MaxRating", float, maximum rating

"decodedKeys": array keys - int encryption key, values - int, index of the "decoded" array.

"decoded": array (array (c0, c1, c2, c3), ... (c0, c1, c2, c3))

c0: string, a commonly used alphabet character used to calculate a key

c1: int, the ordinal of this character in the alphabet.

c2: int, computed encryption key

c3: string, decrypted text

"rating", array (array (k, r), ... (k, r))

k: int encryption key,

r: float rating



5.The GetCharacterFrequency method

Designed to calculate the frequency of unique characters in a given text or alphabet.
Used in DecodingByCharacterFrequency method and to get $alphabet_frequency
see above class instantiation

$object = $CaesarСipher -> GetCharacterFrequency (string | array $buf, null | string | bool $inp_alphabet,
 int | null $decimals, int | null $sort_col);

$buf string | array string or array of text characters.

$inp_alphabet null | string | bool, if an alphabet character string is specified,
then the frequency of the alphabet of the given characters is calculated,
if true, the frequency of only those characters of the alphabet is calculated
which will be found in the source text.

$decimals int | null number of decimal places in character frequency values,
 default value is 2

$sort_col int | null if not given or 2, sorting the result by particular from highest to lowest,
if $sort_col = 1, then sort alphabetically.

if the source text is not specified or if the text is specified, but only unique characters are found in it
$object:
"error" is an int
if $buf is not given, "error" => 1,
if all characters are unique in the ciphertext, "error" => 2

if $inp_alphabet is defined and duplicate characters are found
$object:
"error" is int 0,
"alphabet_frequency", array, an array in which keys, string, alphabet character,
values, float, character frequency in%

if $inp_alphabet is undefined and duplicate characters are found
$object:
"error", int 0
"CharacterFrequency", array (array (ch, fr), ... (ch, fr))
ch: string character
fr: float frequency
"MostFrequentlyCharacter", string character with the maximum frequency value.
"MostFrequentlyCharacterInd", int is the ordinal number of this character in the alphabet.
