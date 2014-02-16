$(document).ready(function () {
    $("button[name^=deletefile]").click(function(){
        var id = $( this ).val();
    	$.post("includes/filedel.inc.php",
    		{
    			uniqid: id
    		},
    		function(data){
    			window.location.reload(true);
    		}
    	);
    });

    $("button[name^=deletemodel]").click(function(){
        var id = $( this ).val();
    	$.post("includes/modeldel.inc.php",
    		{
    			modelID: id
    		},
    		function(data){
    			window.location.reload(true);
    		}
    	);
    });

    $("button[name^=deletelog]").click(function(){
        var id = $( this ).val();
    	$.post("includes/logdel.inc.php",
    		{
    			logID: id
    		},
    		function(data){
    			window.location.reload(true);
    		}
    	);
    });
});
