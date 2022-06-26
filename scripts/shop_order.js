function shop_order(shopname){
    let tbody = document.getElementById('shop_order_tbody');
    let action_item = document.getElementById('status_shop_order');
    let action = action_item.options[action_item.selectedIndex].value;
    let default_null = '<tr><th scope="row">null</th><td>null</td><td>null</td><td>null</td><td>null</td><td>null</td><td>null</td><td>null</td></tr>';
    
    console.log(action);
    console.log("happy bus");
    tbody.innerHTML = default_null;
    $.ajax({
        type: "POST",
        url: "php/shop_order.php",
        data: { action:action, shopname:shopname},
        success: function(results) {
            tbody.innerHTML = results;
        },  
    });
}