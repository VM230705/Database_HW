function refresh_wallet(){
    $.ajax({
        type: "POST",
        url: "php/refresh_wallet.php",
        success: function(results) {
            document.getElementById('user_balance').innerHTML = `${results}`;
        }
    });
}