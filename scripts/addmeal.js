$(document).ready(function (e) {
    $("#add_meal").on('submit',(function(e) {
        // alert("in addmeal.js");
        // console.log("in addmeal.js");
        e.preventDefault();
        $.ajax({
            url: "php/addmeal.php",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend : function(){
                //$("#preview").fadeOut();
                $("#err").fadeOut();
            },
            success: function(data){
                if(data=='invalid'){
                    // invalid file format.
                    console.log("invalid");
                    $("#err").html("Invalid File !").fadeIn();
                }
                else{
                    // view uploaded file.
                    alert(data);
                    // console.log("valid");
                    // console.log(data);
                    // console.log("valid1111");
                    // alert(data);
                    
                    $("#preview").html(data).fadeIn();
                    $("#add_meal")[0].reset(); 
                    if(confirm("Do you want to reload the pages?") == true){
                        window.location.reload();
                    }
                }
            },
            error: function(e) {
                console.log("error");
                $("#err").html(e).fadeIn();
            }          
        });
    }));
});
