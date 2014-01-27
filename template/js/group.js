$(document).ready(function () {
    $("#addmodel2group").click(function(){
        var id = $( this ).val();
    	$.get("includes/groupadd.inc.php",
    		{
    			modelID: id
    		},
    		function(data){
    			$("#addmodel2group").html(data);
    		}
    	);
    });
    
    $("#addlog2group").click(function(){
        var id = $( this ).val();
    	$.get("includes/groupadd.inc.php",
    		{
    			logID: id
    		},
    		function(data){
    			$("#addlog2group").html(data);
    		}
    	);
    });

    $("button[name^=removemodel]").click(function(){
        var id = $( this ).val();
    	$.get("includes/groupdel.inc.php",
    		{
    			modelID: id
    		},
    		function(data){
    			$("#removemodelfromgroup").html(data);
    			window.location.reload(true);
    		}
    	);
    });
    
    $("button[name^=removelog]").click(function(){
        var id = $( this ).val();
    	$.get("includes/groupdel.inc.php",
    		{
    			logID: id,
                ref: document.referrer

    		},
    		function(){
    			window.location.reload(true);
    		}
    	);
    });

    $("button[name^=removegroupmodel]").click(function(){
        var id = $( this ).val();
        var ref = document.referrer ;
    	$.get("includes/groupdel.inc.php",
    		{
    			modelID: id,
                referrer: ref 
    		},
    		function(data){
    			$("#removemodelfromgroup").html(data);
    			window.location.reload(true);
    		}
    	);
    });
    
    $("button[name^=removegrouplog]").click(function(){
        var id = $( this ).val();
        var ref = document.referrer ;
    	$.get("includes/groupdel.inc.php",
    		{
    			logID: id,
                referrer: ref 
    		},
    		function(){
    			window.location.reload(true);
    		}
    	);
    });
});
