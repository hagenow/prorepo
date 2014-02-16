$(document).ready(function () {
    $("button[name^=removefile]").click(function(){
        var id = $( this ).val();
    	$.get("includes/filedel.inc.php",
    		{
    			uniqid: id
    		},
    		function(data){
    			window.location.reload(true);
    		}
    	);
    });
});
