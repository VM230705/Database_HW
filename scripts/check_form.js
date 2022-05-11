function check_form(){
    let pwd = document.getElementById('password').value, 
        re_pwd = document.getElementById('re-password').value,
        valid = true;

    // initial feild
    document.getElementById('retype_check_msg').innerHTML = "";
    document.getElementById('name_check_msg').innerHTML = "";
    document.getElementById('phone_check_msg').innerHTML = "";
    document.getElementById('account_check_msg').innerHTML = "";
    document.getElementById('passwd_check_msg').innerHTML = "";
    document.getElementById('latitude_check_msg').innerHTML = "";
    document.getElementById('longitude_check_msg').innerHTML = "";

    /** Check format */
    // account
    if (/^[a-z0-9A-Z]{1,}$/.test(document.getElementById('Account').value) == false){
        document.getElementById('account_check_msg').innerHTML = "Only allow A-Z, a-z, 0-9 !!";
        valid = false;
    }
    // password
    if (/^[a-z0-9A-Z]{1,}$/.test(document.getElementById('password').value) == false){
        document.getElementById('passwd_check_msg').innerHTML = "Only allow A-Z, a-z, 0-9 !!";
        valid = false;
    }
    // name
    if (/(^[A-Za-z]{1,}$)|(^[A-Za-z]{1,} [A-Za-z]{1,}$)/.test(document.getElementById('name').value) == false){
        document.getElementById('name_check_msg').innerHTML = "Only allow alphabat, there will only allow one space in the middle !!";
        valid = false;
    }
    // phone
    if (/^[0-9]{10}$/.test(document.getElementById('phonenumber').value) == false){
        document.getElementById('phone_check_msg').innerHTML = "Only allow 10 numbers !!";
        valid = false;
    }
    // latitude
    if (/^[0-9]{1,}.[0-9]{1,}$/.test(document.getElementById('latitude').value) == false){
        document.getElementById('latitude_check_msg').innerHTML = "Only allow float numbers !!";
        valid = false;
    }
    // longitude
    if (/^[0-9]{1,}.[0-9]{1,}$/.test(document.getElementById('longitude').value) == false){
        document.getElementById('longitude_check_msg').innerHTML = "Only allow float numbers !!";
        valid = false;
    }


    
    /* pwd dosent match */
    if (pwd != re_pwd){
        document.getElementById('passwd_check_msg').innerHTML = "Passwords aren't matched";
        document.getElementById('retype_check_msg').innerHTML = "Passwords aren't matched";
        valid = false;
    }

    // check empty fields
    if (document.getElementById('name').value == ""){
        document.getElementById('name_check_msg').innerHTML = "This field can't be empty !!";
        valid = false;
    }
    if (document.getElementById('phonenumber').value == ""){
        document.getElementById('phone_check_msg').innerHTML = "This field can't be empty !!";
        valid = false;
    }
    if (document.getElementById('Account').value == ""){
        document.getElementById('account_check_msg').innerHTML = "This field can't be empty !!";
        valid = false;
    }
    if (document.getElementById('password').value == ""){
        document.getElementById('passwd_check_msg').innerHTML = "This field can't be empty !!";
        valid = false;
    }
    alert
    if (document.getElementById('re-password').value == ""){
        document.getElementById('retype_check_msg').innerHTML = "This field can't be empty !!";
        valid = false;
    }
    if (document.getElementById('latitude').value == ""){
        document.getElementById('latitude_check_msg').innerHTML = "This field can't be empty !!";
        valid = false;
    }
    if (document.getElementById('longitude').value == ""){
        document.getElementById('longitude_check_msg').innerHTML = "This field can't be empty !!";
        valid = false;
    }


    if (!valid){
        return false;
    }
    else{
        return true;
    }
}