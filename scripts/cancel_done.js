function cancel_done(request){
    var OID = request.getAttribute("name");
    var ele_id = request.getAttribute("id");
    var data = new FormData();
    var start_id = "start_" + OID;
    var end_id = "end_" + OID; 
    var end_value = document.getElementById(end_id).innerHTML;
    var start_value = document.getElementById(start_id).innerHTML;
    if(end_value){
        alert("This order has been finished or canceled.")
        return;
    }

    data.append("OID", OID);
    data.append("ele_id", ele_id);
    data.append("start", start_value);

    var xhr = new XMLHttpRequest();
    if(confirm('Are you sure you want to update it?') == false){
        return;
    }
    xhr.open("POST", "php/cancel_done.php");
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