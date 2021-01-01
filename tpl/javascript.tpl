   <script type="text/javascript">
      var author;
      var instrument; //active instrument
      var instruments; //list of all instruments selected
      var action;
      $instruments = [];


      // multi-Selection form for instruments
      $(document).ready(function(){
         $('#instrumentID' ).multi();
      });
      // Selection form
      $( '#instrumentID' ).multi({
        non_selected_header: '',
        selected_header: ''
      });
      // Selection form
      $( '#instrumentID' ).multi({
        // enable search
        enable_search: true,
        // placeholder of search input
        search_placeholder: 'Search...'
      });

     {generateJSFunctions}

     $("#nextbutton").fitText(1.1, { minFontSize: '15px', maxFontSize: '30px' });

  </script>
