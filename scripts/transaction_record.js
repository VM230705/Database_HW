function transaction_record(){
    let tbody = document.getElementById('transaction-record-tbody');
    let action_item = document.getElementById('action_choose');
    let action = action_item.options[action_item.selectedIndex].value;
    let default_null = '<tr><th scope="row">null</th><td>null</td><td>null</td><td>null</td><td>null</td></tr>';
    
    console.log(action);
    tbody.innerHTML = default_null;
    $.ajax({
        type: "POST",
        url: "php/transaction_record.php",
        data: { action:action },
        success: function(results) {
            tbody.innerHTML = results;
        }  
    });
}