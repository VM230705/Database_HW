function recharge(){
    let value = document.getElementById('recharge-value').value;
    let valid = true;
    /* check valid */
    if (value == null){
        alert("Fail to get data");
        valid = false;
    }
    // console.log(order);
    if (/^[0-9]{1,}$/.test(value) == false){
        alert(`numbers of recharge value should be integer`);
        valid = false;
    }
    value = Number.parseInt(value);
    if (valid){
        $.ajax({
            type: "POST",
            url: "php/recharge.php",
            data: { rechargeValue: value },
            success: function(results) {
                 alert(results);
                 location.reload();
            }
        });
    }
}