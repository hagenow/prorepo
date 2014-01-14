/* JS File */

// Start Ready
$(document).ready(function() {  

	// Icon Click Focus
	$('div.icon').click(function(){
		$('input#search_mod').focus();
	});

	// Live Search
	// On Search Submit and Get Results
	function search() {
		var query_value = $('input#search_mod').val();
		$('b#search_mod-string').html(query_value);
		if(query_value !== ''){
			$.ajax({
				type: "POST",
				url: "includes/searchmod.inc.php",
				data: { query: query_value },
				cache: false,
				success: function(html){
					$("ul#results-mod").html(html);
				}
			});
		}return false;    
	}

	$("input#search_mod").on("keyup", function(e) {
		// Set Timeout
		clearTimeout($.data(this, 'timer'));

		// Set Search String
		var search_string = $(this).val();

		// Do Search
		if (search_string == '') {
			$("ul#results-mod").fadeOut();
			$('h4#results-mod-text').fadeOut();
		}else{
			$("ul#results-mod").fadeIn();
			$('h4#results-mod-text').fadeIn();
			$(this).data('timer', setTimeout(search, 100));
		};
	});

});
