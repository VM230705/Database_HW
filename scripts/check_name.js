function check_name (uname){
    if (uname != ""){
        // flush field
        document.getElementById("account_check_msg").innerHTML = "";
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function (){
            let message;
            // on status code 200
            if (this.readyState == 4 && this.status == 200){
                switch (this.responseText) {
                    case 'YES':
                        message = "available account";
                        break;
                    case 'NO':
                        message = "This account is not available";
                        break;
                    default:
                        message = "There is something wrong QAQ.";
                        // message = this.responseText
                        break;
                }
                document.getElementById("account_check_msg").innerHTML = message;
            }
        }

        // AJAX
        xhttp.open("POST", "php/check_name.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("uname="+uname);
    }
    else{
        document.getElementById("account_check_msg").innerHTML = "This field can't be empty !!";
    }
}