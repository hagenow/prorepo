$(document).ready(function () {
    $('#toggleall').click(function() {
        $('input[name^="user"]').each(function(){
            // toggle checkbox
            $(this).prop('checked',!$(this).prop('checked'));
        });
    });
});
