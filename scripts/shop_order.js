var count = 0;

function shop_order(shopname){
    let tbody = document.getElementById('shop_order_tbody');
    let action_item = document.getElementById('status_shop_order');
    let action = action_item.options[action_item.selectedIndex].value;
    let default_null = '<tr><th scope="row">null</th><td>null</td><td>null</td><td>null</td><td>null</td><td>null</td><td>null</td><td>null</td></tr>';
    
    console.log(action);
    tbody.innerHTML = default_null;
    $.ajax({
        type: "POST",
        url: "php/shop_order.php",
        data: { action:action, shopname:shopname},
        success: function(results) {
            count++;
            tbody.innerHTML = results;
            shop_order_detail(shopname, action, count);
        },  
    });

}

function shop_order_detail(shopname, status, count){
    $.ajax({
        type: "POST",
        url: "php/shop_order_detail.php",
        data: { shopname:shopname, status:status, count:count},
        success: function(response) {
            $('.script').remove();
            $("#container").append(response);
        },  
    });
}