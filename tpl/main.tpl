{header}
   <body onload="onLoadFunction()" style="margin:0;">
      <h1>Elog Event Entry</h1>
      <p>
	   <div id="doneid" 
			style="text-align:center;font-size: 18px;color: white; font-family:Arial"> 
           </div>
      </p>

	   <div id="instr_form_id">
	       <form class="form-card" style="color: #66F2D0">
		   <fieldset class="form-fieldset">
                       <legend class="form-legend">
				{cruiseID}
                       </legend>
	               <p style="font-size:20px;color:#ffffff;text-align:center" id="peoplelist">
				{authorList}
                       </p>
                       <p style="font-size:20px; color: #FFA45C;">
				{instrumentList}
                       <p>
                  </fieldset>
              </form>
           </div>


      <div id="bigbuttons" white-space="normal" word-wrap="break-word">
                       <button onclick="displayInstruments()" class="block" id="nextbutton">Next</button>
      </div>

      <p><span id="elogCmd" class="progressReport"></span></p>
      <div style="display:none;" id="myDiv" class="animate-bottom"> </div>
      <div id="loader"></div>
      <div style="display:none;" id="overlay" class="animationOverlay"></div>

   </body>
      <footer><h6 id="versionfooter" style="text-align:center">Version 2.0.1</h6></footer>
{javascript}
</html>
