<?php
	$CC="gcc";
	$out="timeout 5s ./a.out";
	$code=$_POST["code"];
	$input=$_POST["input"];
	$filename_code="main.c";
	$filename_in="input.txt";
	$filename_error="error.txt";
	$executable="a.out";
	$command=$CC." -lm ".$filename_code;	
	$command_error=$command." 2>".$filename_error;
	$check=0;

	//if(trim($code)=="")
	//die("The code area is empty");
	
// $os = array("findfirst",
// "findnext",
// "ffblk - struct",
// "fopen",
// "fread",
// "fwrite",
// "system",
// "fork",
// "fseek",
// "fprintf",
// ".bat",
// "LPDWORD",
// "HGLOBAL",
// "LPVOID",
// "HINSTANCE",
// "FARPROC",
// "WINAPI",
// "WinMain",
// "WIN32_FIND_DATA",
// "LPSTR",
// "LoadLibrary",
// "GetProcAddress",
// "sleep",
// "wait",
// "GetModuleFileName",
// "CreateFile",
// "GlobalAlloc",
// "GlobalLock",
// "FindExecutable",
// "kernel32.dll",
// "APIENTRY",
// "HKEY",
// "GetWindowsDirectory",
// "HMODULE",
// "DWORD",
// "windows.h",
// "RegCreateKey",
// "RegSetValueEx",
// "RegCloseKey",
// "GetSystemDirectory",
// "HWND",
// "ShowWindow",
// "GetModuleFileName",
// "delay");
$p=$_SERVER['DOCUMENT_ROOT']."/compilers/words.txt";

$file = file_get_contents($p);


$spam_words = file($p, FILE_IGNORE_NEW_LINES);

for ($x = 0; $x < sizeof($spam_words); $x++) {
  $spam_words[$x] = substr($spam_words[$x], 0, -2);

 } 
$os=$spam_words;


$wd="";
// function match_my_string($needle , $haystack ) {
//   if (strpos($haystack, $needle) !== false) return true;
//   else return false;
// }
$file_code=fopen($filename_code,"w+");
	fwrite($file_code,$code);
	fclose($file_code);


$file_code = file_get_contents($filename_code);
$os_code = str_word_count($file_code, 1);

$check1=false;


foreach ($os as $char1) {
if (in_array($char1, $os_code)) {
    $check1=true;
	$wd=$char1;
	break;
}



}

$statement=" cannot be used as it can be a malware";
$wd=$wd.$statement;
if($check1)
{

echo "<textarea id='div' class=\"form-control\" name=\"output\" rows=\"10\" cols=\"50\">$wd</textarea><br><br>";
}


else
{

	$file_code=fopen($filename_code,"w+");
	fwrite($file_code,$code);
	fclose($file_code);
	$file_in=fopen($filename_in,"w+");
	fwrite($file_in,$input);
	fclose($file_in);
	exec("chmod 777 $executable"); 
	exec("chmod 777 $filename_error");	

	shell_exec($command_error);
	$error=file_get_contents($filename_error);
	$executionStartTime = microtime(true);

	if(trim($error)=="")
	{
		if(trim($input)=="")
		{
			$output=shell_exec($out);
		}
		else
		{
			$out=$out." < ".$filename_in;
			$output=shell_exec($out);
		}
		//echo "<pre>$output</pre>";
        echo "<textarea id='div' class=\"form-control\" name=\"output\" rows=\"10\" cols=\"50\">$output</textarea><br><br>";
	}
	else if(!strpos($error,"error"))
	{
		echo "<pre>$error</pre>";
		if(trim($input)=="")
		{
			$output=shell_exec($out);
		}
		else
		{
			$out=$out." < ".$filename_in;
			$output=shell_exec($out);
		}
		//echo "<pre>$output</pre>";
                echo "<textarea id='div' class=\"form-control\" name=\"output\" rows=\"10\" cols=\"50\">$output</textarea><br><br>";
	}
	else
	{
		echo "<pre>$error</pre>";
		$check=1;
	}
	$executionEndTime = microtime(true);
	$seconds = $executionEndTime - $executionStartTime;
	$seconds = sprintf('%0.2f', $seconds);
	echo "<pre>Compiled And Executed In: $seconds s</pre>";
	if($check==1)
	{
		echo "<pre>Verdict : Compile time Error</pre>";
	}
	else if($check==0 && $seconds>3)
	{
		echo "<pre>Verdict : Time Limit Exceeded</pre>";
	}
	else if(trim($output)=="")
	{
		echo "<pre>Verdict : Wrong Answer</pre>";
	}
	else if($check==0)
	{
		echo "<pre>Verdict : Accepted</pre>";
	}


	exec("rm $filename_code");
	exec("rm *.o");
	exec("rm *.txt");
	exec("rm $executable");
}

?>
