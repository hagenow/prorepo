/* JS File */

// Start Ready
$(document).ready(function() {  

	// Icon Click Focus
	$('div.icon').click(function(){
		$('input#search_cat').focus();
	});

	// Live Search
	// On Search Submit and Get Results
	function search() {
		var query_value = $('input#search_cat').val();
		$('b#search_cat-string').html(query_value);
		if(query_value !== ''){
			$.ajax({
				type: "POST",
				url: "includes/searchcat.inc.php",
				data: { query: query_value },
				cache: false,
				success: function(html){
					$("ul#results-cat").html(html);
				}
			});
		}return false;    
	}

	$("input#search_cat").on("keyup", function(e) {
		// Set Timeout
		clearTimeout($.data(this, 'timer'));

		// Set Search String
		var search_string = $(this).val();

		// Do Search
		if (search_string == '') {
			$("ul#results-cat").fadeOut();
			$('h4#results-cat-text').fadeOut();
		}else{
			$("ul#results-cat").fadeIn();
			$('h4#results-cat-text').fadeIn();
			$(this).data('timer', setTimeout(search, 100));
		};
	});

});

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

// Start Ready
$(document).ready(function() {  

	// Icon Click Focus
	$('div.icon').click(function(){
		$('input#search_log').focus();
	});

	// Live Search
	// On Search Submit and Get Results
	function search() {
		var query_value = $('input#search_log').val();
		$('b#search_log-string').html(query_value);
		if(query_value !== ''){
			$.ajax({
				type: "POST",
				url: "includes/searchlog.inc.php",
				data: { query: query_value },
				cache: false,
				success: function(html){
					$("ul#results-log").html(html);
				}
			});
		}return false;    
	}

	$("input#search_log").on("keyup", function(e) {
		// Set Timeout
		clearTimeout($.data(this, 'timer'));

		// Set Search String
		var search_string = $(this).val();

		// Do Search
		if (search_string == '') {
			$("ul#results-log").fadeOut();
			$('h4#results-log-text').fadeOut();
		}else{
			$("ul#results-log").fadeIn();
			$('h4#results-log-text').fadeIn();
			$(this).data('timer', setTimeout(search, 100));
		};
	});

});
