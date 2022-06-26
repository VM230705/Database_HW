$(document).ready(function(){
    $("#shopname").keyup(function(){
        var shopname = $(this).val().trim();
        if(shopname != ''){
            $.ajax({
                url: 'php/ajax_shop.php',
                type: 'post',
                data: {shopname: shopname},

                success: function(response){
                $('#sname_response').html(response);
                }
            });
        }
        else{
            $("#sname_response").html("");
        }
    });
});

