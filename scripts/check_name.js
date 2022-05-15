function check_name (uname){
    // check if empty and the format
    if (uname != "" && /^[a-z0-9A-Z]{1,}$/.test(document.getElementById('Account').value) == true){
        // flush field
        document.getElementById("account_check_msg").innerHTML = "";
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function (){
            let message;
            // on status code 200
            if (this.readyState == 4 && this.status == 200){
                switch (this.responseText) {
                    case 'YES':
                        message = "unused account name";
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
    else if (uname == ""){
        document.getElementById("account_check_msg").innerHTML = "This field can't be empty !!";
    }
    else if (/^[a-z0-9A-Z]{1,}$/.test(document.getElementById('Account').value) == false){
        document.getElementById('account_check_msg').innerHTML = "Only allow A-Z, a-z, 0-9 !!";
    }
}