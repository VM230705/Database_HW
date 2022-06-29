function shop_cancel(request){
    // var shopname = document.getElementById("status_shop_order").getAttribute("name");
    // console.log("shopname = ", shopname);
    var OID = request.getAttribute("name");
    var data = new FormData();


    data.append("OID", OID);
;

    var xhr = new XMLHttpRequest();
    if(confirm('Are you sure you want to update it?') == false){
        return;
    }
    xhr.open("POST", "php/shop_cancel.php");
    xhr.onload = function(){
        if(confirm(this.response) == true){
            window.location.href = 'nav.php';
        }
        console.log(this.response);
    };
    xhr.send(data);
    // location.reload();
    
    return false;
}