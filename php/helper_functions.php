<?PHP
function getCruiseID(){
	$cfg_filename = "/shipdata/cruise_cfg/current_cfg";
	$filehandle = fopen($cfg_filename, "r") or die("Unable to open $cfg_filename!");
	while(!feof($filehandle)) {
		$line = fgets($filehandle); //get line
		$match = preg_match("/(CRUISEID)(.*)/", $line, $matches); //search for Cruise ID
		if($match){
			$found = preg_match("/(= )(.*)/", $matches[2], $id); //get ID number
			if($found){
				$cruise = str_replace(' ', '', $id); // Replaces all spaces with nothing.
				$cruiseID = $cruise[2]; //append _SE
			}
		}
	}
       	fclose($filehandle);
	return $cruiseID;
}

//get cruise ID from Linux ps command
function getCruiseIDByPID(){
	$cmd = "ps -aelf";
	$result = shell_exec($cmd);
	if(!($result)){
	       	echo "<br> $cmd not found!<br>";
	}
	//grab elogd process
	$match = preg_match('/(elogd.*)/', $result, $matches);
	if($match){
		$ps_fields = preg_split("/[\s,]+/", $matches[0]);
		$found=false;
		foreach ($ps_fields as $cruiseID) {
			if( preg_match("/shipdata/",$cruiseID) ) {
				$found=true;
				//get first dir after shipdata
				$pattern = '/\/shipdata\/(\w+).*/';
				$replacement = '/${1}/'; 
				$cruiseID = preg_replace($pattern, $replacement, $cruiseID);
				//strip off forward slashes /
				$pattern = '/\//';
				$replacement = ''; 
				$sendCruiseID = preg_replace($pattern, $replacement, $cruiseID);
				$break;
			}
		}
		if(!($found)){
			echo "<br> shipdata path not found!<br>";
	       	}
	} else {
	       	echo "<br> elog process not found!<br>";
	}
	return $sendCruiseID;
}
//for debugging
//echo getCruiseID();
function getPeopleList(){
     $cruiseID=getCruiseID();
     $peoplecsv = "/shipdata/$cruiseID/r2r/eventlog/elog/ecfm/ecfm_people.csv";
     $row = 1;
     if(file_exists($peoplecsv)){
	     $handle = fopen($peoplecsv, "r") or die("$peoplecsv not found!");
	     $no_of_lines = count(file($peoplecsv));
	     if($no_of_lines > 1){
		     $html_code =  "<label for=\"authorId\">Author:</label>\n";
		     $html_code .= "<select name=\"author\" id=\"authorId\" style=\"font-size:20px;\">\n";
		     $html_code .= "<option value=\"\">Please Select</>\n";
		     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			     $num = count($data);
			     $row++;
			     if($row > 2){
				     $html_code .= "                <option value=" . $data[3] . ">$data[3]</>\n";
			     }
		     }
		     fclose($handle);
		     $html_code .= "</select>";
		     $html_code .= "<br>";
	     } else {
		     $html_code = "Please Create a Participant List!";
	     }
     } else {
	     $html_code = "Please Create a Participant List!";
     }
     return $html_code;
}


//get list of instruments from csv file
function getInstrumentList(){
   $cruiseID=getCruiseID();
   $peoplecsv = "/shipdata/$cruiseID/r2r/eventlog/elog/ecfm/ecfm_people.csv";
   $instrumentcsv = "/shipdata/$cruiseID/r2r/eventlog/elog/ecfm/ecfm_instrument_list.csv";
   $row = 1;
   if(file_exists($peoplecsv)){
	   $html_code = "<label for=\"instrumentID\">Instruments:</label>";
	   $html_code .= "<select multiple=\"multiple\" name=\"instrument\" id=\"instrumentID\">";
	   $handle = fopen($instrumentcsv, "r") or die("Unable to open $handle!");
	   while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		   $num = count($data);
		   $row++;
		   if($row > 1){
			   $html_code .= "                <option value=\"" . $data[6] . "\">$data[6]</>\n";
		   }
	   }
	   fclose($handle);
	   $html_code .= "</select>\n";
	   $html_code .= "<br>\n";
   }
   return $html_code;
}


function insertNextButton(){
   $cruiseID=getCruiseID();
   $peoplecsv = "/shipdata/$cruiseID/r2r/eventlog/elog/ecfm/ecfm_people.csv";
if(file_exists($peoplecsv)){
	echo "            <button onclick=\"displayInstruments()\" class=\"block\" id=\"nextbutton\">Next</button>\n";
   }
}

function displayCruiseID(){
   $cruiseID=getCruiseID();
   echo "Cruise ID: $cruiseID";
}
function generateJSActionFunction(){
$html_code = "function displayActions(instrumentSelection) { //display all actions for instrument selected\n";
$html_code .= "    //assign to global var to pass to server\n";
$html_code .= "    instrument = instrumentSelection;\n";
$html_code .= "    //get list of all bigbuttons\n";
$html_code .= "    var bigbuttonslist = document.getElementsByClassName('bigbutton');\n";
$html_code .= "    var numBigButtons = bigbuttonslist.length;\n";
$html_code .= "	  //remove all bigbuttons \n";
$html_code .= "	  var index = 0;\n";
$html_code .= "    while ((bigbuttonslist.length) && (index < numBigButtons)) {\n";
$html_code .= "       document.getElementById('bigbuttons').removeChild(bigbuttonslist[index]);\n";
$html_code .= "    }\n";
$html_code .= "    //display new heading\n";
$html_code .= "    document.getElementById(\"doneid\").innerHTML = \"Select An Action for \" + instrumentSelection;\n";
$html_code .= "    //remove any existing radio buttons\n";
$html_code .= "    \$('.action_button').empty(); // delete old radio buttons\n";
$html_code .= "    var button_div = document.getElementById('bigbuttons');\n";
$html_code .= "    //get list of actions for each instrument from csv file\n";
$html_code .= "    switch (instrumentSelection) { \n";
          $cruiseID=getCruiseID();
          $instrumentcsv = "/shipdata/$cruiseID/r2r/eventlog/elog/ecfm/ecfm_instrument_list.csv";
          $row = 1;
          $handle = fopen($instrumentcsv, "r") or die("Unable to open $handle!");
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
               $num = count($data);
               $row++;
               if($row > 1){
       	          $index=0;
       	          $html_code .= "        case '" . $data[6] . "':\n" ;
       	          $optionList = explode(";", $data[7]);
       	          foreach($optionList as $option){
       			$html_code .= "                  var btn" . $index . " = document.createElement('button');\n";
       			$html_code .= "                  btn" . $index . ".setAttribute('class', 'bigbutton');\n";
       			$html_code .= "                  btn" . $index . ".setAttribute('id', '" . $option . "');\n";
       			$html_code .= "                  btn" . $index . ".setAttribute('value', '" . $option . "');\n";
       			$html_code .= "                  btn" . $index . ".setAttribute('onClick', 'runElogClient(\"" . $option . "\")');\n";
       			$html_code .= "                  var n" . $index . " = document.createTextNode(\"" . $option . "\");\n";
       			$html_code .= "                  btn" . $index . ".appendChild(n" . $index . ");\n";
       			$html_code .= "                  button_div" . ".appendChild(btn" . $index . ");\n";
       			$html_code .= "                  \$(\"#" . $option . "\").fitText(1.1, { minFontSize: '20px', maxFontSize: '30px' });\n";
       		        $html_code .= "\n";
       			$index += 1;
       	         }
       	         $html_code .= "                  break;\n\n";
              }
         }
         fclose($handle);
$html_code .= "       }\n";
$html_code .= "     }\n";
return $html_code;
}
?>
