function register(){
    try{
        let account = document.getElementById('Account').value,
            password = document.getElementById('password').value,
            name = document.getElementById('name').value,
            phonenumber = document.getElementById('phonenumber').value,
            latitdue = document.getElementById('latitude').value,
            longitude = document.getElementById('longitude').value;
        
        // Sync HTTP request
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function (){
            // get response
            if (this.readyState == 4 && this.status == 200){
                switch (this.responseText){
                    case 'Success':
                        message = "註冊成功";
                        window.location.href = "index.html";
                        break;
                    case 'Exist':
                        message = "帳號已被註冊";
                        break;
                    default:
                        message = this.responseText;
                        break;
                }
                alert (message);
            }
        }

        // AJAX request
        xhr.open("POST", "php/register.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(`name=${name}&phonenumber=${phonenumber}&account=${account}&password=${password}&latitude=${latitdue}&longitude=${longitude}`);
    }
    catch (e){
        alert (e.message);
    }
}