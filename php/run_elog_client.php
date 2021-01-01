<?PHP
//Note: this script can be run standalone for testing
//php  run_elog_client.php
$cfg_filename = "/shipdata/cruise_cfg/current_cfg";
$filehandle = fopen($cfg_filename, "r") or die("Unable to open file!");
while(!feof($filehandle)) {
        $line = fgets($filehandle); //get line
        $match = preg_match("/(CRUISEID)(.*)/", $line, $matches); //search for Cruise ID
	if($match){
		$found = preg_match("/(= )(.*)/", $matches[2], $id); //get ID number
                if($found){
                        $cruise = str_replace(' ', '', $id); // Replaces all spaces with nothing.
                        $cruiseID = "$cruise[2]" . "-SE"; //append _SE
		}
        }
}
fclose($filehandle);


// get the author parameter from URL
// default to test for tesing this script standalone
if( !($author = $_REQUEST["author"])){
	$author = "test";
}
if( !($instrument = $_REQUEST["instrument"])){
	$instrument = "test";
}
if( !($action = $_REQUEST["action"]) )  {
	$action = "test";
}

$cmd = "/home/r2root/current/bin/elog/elog -h localhost -p 8090  -l \"$cruiseID\" -a Author=\"$author\" -a Instrument=\"$instrument\" -a Action=\"$action\" \" \"";
//to debugging this code, run "php run_elog_client.php"
//echo "<pre>DEBUG: $cmd";

$result = shell_exec($cmd);
//send result back to calling javascript function
echo "$result";
?>
