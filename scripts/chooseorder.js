function choose_order(){
    let tbody = document.getElementById('myorder-tbody');
    let order_item = document.getElementById('order_choose');
    // console.log(order_item);
    let action = order_item.options[order_item.selectedIndex].value;
    let default_null = '<tr><th scope="row">null</th><td>null</td><td>null</td><td>null</td><td>null</td><td>null</td><td>null</td></tr>';

    console.log(action);
    tbody.innerHTML = default_null;
    $.ajax(
        {
            type:"POST",
            url: "php/chooseorder.php",
            data: {action:action},
            success: function(results){
                tbody.innerHTML = results;
            }
        }
    );

}