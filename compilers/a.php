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

$p=$_SERVER['DOCUMENT_ROOT']."/compilers/words.txt";


    $myfile = fopen($p, "r") or die("Unable to open file!");



// Output one line until end-of-file
while(!feof($myfile)) {
    $os[] = fgets($myfile);
}
fclose($myfile);





$wd="";

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




function match_my_string($needle , $haystack ) {
  if (strpos($haystack, $needle) !== false) return true;
  else return false;
}

$check=false;
foreach ($os as $char1) {

$check=match_my_string($char1,$code);
if($check)
	{$wd=$char1;
		break;

}
}

$statement=" cannot be used as it can be a malware";
$wd=$wd.$statement;
if($check)
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
		echo "<pre>Verdict : CE</pre>";
	}
	else if($check==0 && $seconds>3)
	{
		echo "<pre>Verdict : TLE</pre>";
	}
	else if(trim($output)=="")
	{
		echo "<pre>Verdict : WA</pre>";
	}
	else if($check==0)
	{
		echo "<pre>Verdict : AC</pre>";
	}


	exec("rm $filename_code");
	exec("rm *.o");
	exec("rm *.txt");
	exec("rm $executable");
}

?>
