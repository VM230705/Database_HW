function multiple_done(){
    let multiple_done_script = document.getElementById("multiple_done_script");
    let checkbox = (document).getElementsByName("s_checkbox[]");
    let flag;
    if($(".s_checkbox").is(":checked")){
        console.log("someone is checked");
        flag = confirm('Are you sure to finish the order(s)?');  
    }else{
        window.alert("No one is checked.");
        console.log("No one is checked.");
    }
    if(!flag){
        return;
    }
    for (let i=0;i<checkbox.length;i++){
        let temp = checkbox[i].id;
        if ($("#"+temp+"").get(0).checked) { 
            console.log(temp," is checked");
            $.ajax(
                {
                    type:"POST",
                    url: "php/multiple_done.php",
                    data: {OID:checkbox[i].value},
                    success: function(results){
                        console.log(results);
                        if(results=="Done Failed"){
                            window.alert("Done Failed")
                            console.log("Done Failed");
                        }
                    }
                }
            );
       }
    }
    location.replace("./nav.php");
}