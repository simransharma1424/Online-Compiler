<html>
<body>
<?php
 
$p=$_SERVER['DOCUMENT_ROOT']."/compilers/words.txt";



function match_my_string($haystack ,$needle) {
  if (strpos($haystack, $needle) !== false) return 1;
  else 
  	return 0;
}







$file = file_get_contents($p);

$os = str_word_count($file, 1);
//echo $os[2];


if("abicompat"==$os[0])
	echo "string";

$code="hello i am aman ar cflow abicompat ";



?>
</body>
</html>