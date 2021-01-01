     var times = 0;

     function doclick() {
        times++;
        location.hash = times;
     }
     window.onhashchange = function () {
        if (location.hash.length > 0) {
                times = parseInt(location.hash.replace('#', ''), 10);
                current_hash = window.location.hash;
        } else {
                times = 0;
                location.reload();
        }
     }



      //turn off loader animation
      function onLoadFunction() {
	document.getElementById("loader").style.display = "none";
	document.getElementById("myDiv").style.display = "block";
      } 

      function reDisplayInstruments() { //display all instruments selected after submitting
	//display new heading
	document.getElementById("doneid").innerHTML += "Select Another Instrument or Home";

	//delete bigbuttons and div elements 
	var bigbuttonslist = document.getElementById('bigbuttons');
	while (bigbuttonslist.firstChild) {
		bigbuttonslist.removeChild(bigbuttonslist.lastChild);
	}

	//create big buttons one for each instrument
	var button_div = document.getElementById('bigbuttons');
	for (index = 0; index < (instruments.length / 2); index++) {
		instrument = instruments[index];
		instrument = instrument.trim();
		var bigbutton = document.createElement('button');
		instrument_id = instrument.replace(/\s/g,'_');
		instrument_id = instrument_id.replace(/[^a-zA-Z0-9]+/g,'_');
		bigbutton.setAttribute('class', 'bigbutton');
		bigbutton.setAttribute('id', instrument_id);
		bigbutton.setAttribute('onClick', "displayActions(\"" + instrument + "\")");
		var l0 = document.createElement("label");
		var node0 = document.createTextNode(instrument);
		bigbutton.appendChild(node0);
		var divName = "div_" + instrument;
		var divId = document.getElementById(divName);
		bigbuttons.appendChild(bigbutton);
		$("#" + instrument_id).fitText(1.1, { minFontSize: '20px', maxFontSize: '30px' });
	}
      } 

      function displayInstruments() { //display all instruments selected

	//needed for browser back button to return to home
	doclick();

	//validate and retrieve author from drop-down list
	author = document.getElementById("authorId").value;
	if (author == "") {
		alert("Name must be filled out");
		return false;
	}

	//get list of instruments from multi-selection drop-down list
	instruments = $("#instrumentID").val();
	if (instruments == "") {
		alert("Instrument must be filled out");
		return false;
	}


	//remove input form if data valid
	var instr_div = document.getElementById('instr_form_id');
	while (instr_div.firstChild) {
		instr_div.removeChild(instr_div.firstChild);
	}

	//remove Next button if data valid
	var next_button = document.getElementById('nextbutton');
	next_button.remove();

	//delete version footer
	var versionFooter = document.getElementById("versionfooter");
	versionFooter.remove();
	//display new heading
	document.getElementById("doneid").innerHTML = "Select An Instrument";

	//Display selected items
	//get list of instruments
	$.each(instruments, function (key, value) {
		instruments.push(value);
	});


	//create big buttons and div elements one for each instrument
	var button_div = document.getElementById('bigbuttons');
	for (index = 0; index < (instruments.length / 2); index++) {
		instrument = instruments[index];
		instrument = instrument.trim();
		var bigbutton = document.createElement('button');
		bigbutton.setAttribute('class', 'bigbutton');
		instrument_id = instrument.replace(/\s/g,'_');
		instrument_id = instrument_id.replace(/[^a-zA-Z0-9]+/g,'_');
		bigbutton.setAttribute('id', instrument_id);
		bigbutton.setAttribute('onClick', "displayActions(\"" + instrument + "\")");
		var node0 = document.createTextNode(instrument);
		bigbutton.appendChild(node0);
		bigbuttons.appendChild(bigbutton);
		$("#" + instrument_id).fitText(1.1, { minFontSize: '20px', maxFontSize: '30px' });
	}

	//Create Home button 
	var homeButton = document.createElement('button');
	homeButton.setAttribute('class', 'block');
	homeButton.setAttribute('onClick', "location.reload()");
	homeButton.setAttribute('id', "home_id");
	var sbtn_txt_node = document.createTextNode('Home');
	homeButton.appendChild(sbtn_txt_node);
	document.body.appendChild(homeButton);
	$("#home_id").fitText(1.1, { minFontSize: '15px', maxFontSize: '30px' });
	return;
      } 

      function runElogClient(actionName) { //submit author, instrument, and action to server

	action = actionName;
	//get div to report return condition
	var reportDiv = document.getElementById('doneid');
	var reportContent = document.createTextNode(":Success!");


	//turn loader on
        var overlayID = document.getElementById("overlay");
        overlayID.style.display="block";

	document.getElementById("loader").style.display = "";
	document.getElementById("myDiv").style.display = "";
	document.getElementById("doneid").innerHTML = "Submitting " + author + ", " + instrument + ": " + action + ", please wait...";

	// submit to server
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("loader").style.display = "none";
			overlayID.style.display="none";
			if(! (this.responseText) ){
				document.getElementById("doneid").innerHTML = "ERROR: elog not found!";
			} else {
				str = this.responseText;
				if( str.match(/ID/) ){
					reportDiv.appendChild(reportContent);
					document.getElementById("doneid").innerHTML = "<b>Success!</b><p></p>";
					reDisplayInstruments();
				} else {
					document.getElementById("doneid").innerHTML = this.responseText;
					document.getElementById("doneid").innerHTML += "ERROR:Unable to create log";
				}
			}
		}
	};
	xmlhttp.open("GET", "php/run_elog_client.php?author=" + author + "&instrument=" + instrument + "&action=" + action, true);
	xmlhttp.send();

	return;
      } 
      
