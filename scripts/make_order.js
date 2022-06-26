// global variable
var meal_list = [];
var ordered = [];
var valid = false;
var current_shop = "";

// get order items
function get_order(shop_name){
    let total = 0;
    valid = true;
    let wallet = document.getElementById('user_balance').innerHTML;
    ordered = [];
    // check order numbers in meal list
    for (let i=0; i<meal_list.length; i++){
        let meal_name = meal_list[i];
        let order = document.getElementById(`quantity-${shop_name}-${meal_name}`).value;
        let shop_quantity = document.getElementById(`shop-quantity-${shop_name}-${meal_name}`).innerHTML;
        let meal_price = document.getElementById(`shop-price-${shop_name}-${meal_name}`).innerHTML;
        /* check valid */
        if (order == null || shop_quantity == null){
            alert("Fail to get data");
            valid = false;
        }
        // console.log(order);
        if (/^[0-9]{1,}$/.test(order) == false){
            alert(`numbers of order value "${meal_name}" should be integer`);
            valid = false;
        }
        order = Number.parseInt(order, 10);
        if (order > shop_quantity){
            alert(`numbers of order value "${meal_name}" have larger amount than the shop`);
            valid = false;
        }
        // append to total
        total += meal_price * order;
        if (valid == true && order > 0){
            ordered.push([
                meal_name, meal_price, order
            ]);
        }
    }
    // check wallet
    console.log(`total: ${total}, wallet: ${wallet}`);
    if (total > wallet){
        alert("You don't have enough money !!");
        valid = false;
    }
    else if (valid && total == 0){
        alert("Haven't Order Anything !!");
        valid = false;
    }
    else if (valid){
    // Pass the parameter to backend
        console.log(`total price: ${total}`);
        check_order_info(ordered, shop_name, total);
    }
}

// order information -> show ordered information and confirm
function check_order_info(ordered, shop_name, total){
    let tbody = document.getElementById('check-order-info-tbody');
    let total_p = document.getElementById('check-order-info-total');
    
    // Clear formor
    tbody.innerHTML = "";
    total_p.innerHTML = "";

    // Append ordered
    if (ordered.length >= 1){
        for (let i=0; i<ordered.length; i++){
            let item_tr = document.createElement('tr');
            let img_td = document.createElement('td');
            let name_td = document.createElement('td');
            let img = document.getElementById(`img-${shop_name}-${ordered[i][0]}`).cloneNode(true);
            let price_td = document.createElement('td');
            let quantity_td = document.createElement('td');
    
            img_td.appendChild(img);
            name_td.appendChild(document.createTextNode(`${ordered[i][0]}`));
            price_td.appendChild(document.createTextNode(`${ordered[i][1]}`));
            quantity_td.appendChild(document.createTextNode(`${ordered[i][2]}`));
    
            item_tr.appendChild(img_td);
            item_tr.appendChild(name_td);
            item_tr.appendChild(price_td);
            item_tr.appendChild(quantity_td);
    
            tbody.appendChild(item_tr);
        }
    
        total_p.appendChild(document.createTextNode(`${total}`));
        current_shop = shop_name;
        $('#check-order-info').modal('show');
    }
}

// Pass information to backend
function start_transaction(){
    // Get the ordered array and pass to backend
    console.log(ordered);
    $.ajax({
        type: "POST",
        url: "php/make_order.php",
        data: { activitiesArray: ordered, shopName: `${current_shop}` },
        success: function(results) {
             alert(results);
             location.reload();
        }
    });
}

// store current item to global variable to summarize order
function menu_store_items(shop_name){
    // empty meal list, add foods via shop name
    meal_list = [];
    let i = 1;
    for (i=1; document.getElementById(`row-${shop_name}-${i}`); i++){
    let shop_row = document.getElementById(`row-${shop_name}-${i}`);
    let meal_name = document.getElementById(`meal-${shop_name}-${i}`);
    
    // store to the meal_list
    meal_list.push(meal_name.innerHTML);
    }
    // console.log(meal_list);
}