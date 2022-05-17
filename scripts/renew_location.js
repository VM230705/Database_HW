function renew_location (){
    let latitude = document.getElementById("latitude").value,
        longitude = document.getElementById("longitude").value;

    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function (){
        // get response
        if (this.readyState == 4 && this.status == 200){
            document.getElementById('user_profile').innerHTML = this.responseText;
        }
    }

    // AJAX request
    xhr.open("POST", "php/renew_location.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(`latitude=${latitude}&longitude=${longitude}`);
}