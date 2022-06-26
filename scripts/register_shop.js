function register_shop(){
    var data = new FormData();
    data.append("shopname", document.getElementById("shopname").value);
    data.append("category", document.getElementById("ex5").value);
    data.append("latitude", document.getElementById("ex6").value);
    data.append("longitude", document.getElementById("ex8").value);
    data.append("account", document.getElementById("username").innerHTML);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/register_shop.php");
    xhr.onload = function(){
        alert(this.response);
        console.log(this.response);
        if (this.response == "shop name has been registered!!"){
            document.getElementById("register_btn").disabled = true;
            document.getElementById('add_container').style.visibility = 'visible';     // Hide
            window.location.reload();
        }
    };
    xhr.send(data);
    return false;
}