$(document).on("click", '.fa-plus', function( ) {

    $.ajax({
        type: "POST",
        cashe: false,
        url: "/site/line/",
        data: ({_csrf: $('meta[name="csrf-token"]').attr("content")}),
        success:
                function(msg) {

                    $(".editable").remove();
                    $(".table-fill tbody").prepend(msg);

                }
    });


});

$(document).on("click", '.sum_relative  .fa-minus-circle', function( ) {


    var th = $(this);
    var block = th.closest("tr");
    var id = block.attr("id");


    if (id)
    {



        $.ajax({
            type: "POST",
            cashe: false,
            url: "/admin/transaction/ajaxdelete",
           
            data: ({id: id, _csrf: $('meta[name="csrf-token"]').attr("content")}),
            success:
                    function(msg) { 
                        $(".result_line").replaceWith(msg);
                    }
        });

    }


    $(this).closest("tr").remove();


});



$(document).on("click", '.sum_relative  .fa-plus-circle', function( ) {



    var category_id = $(this).closest("tr").find("button").attr("id");
    var sum = $(this).closest("tr").find("input").val();

    var th = $(this);


    $.ajax({
        type: "POST",
        cashe: false,
        url: "/admin/transaction/ajaxcreate",
        dataType: 'json',
        data: ({sum: sum, category_id: category_id, _csrf: $('meta[name="csrf-token"]').attr("content")}),
        success:
                function(msg) {

                    if (msg.error)
                    {
                        var result = "";

                        $.each(msg.error, function(key, value) {
                            result += "  " + value;
                        });


                        alert(result);

                    }
                    else
                    {
 
                        $.ajax({
                            type: "POST",
                            cashe: false,
                            url: "/site/linetext/",
                            data: ({ID: msg.success, _csrf: $('meta[name="csrf-token"]').attr("content")}),
                            success:
                                    function(msg) {
                                        var html = $("<p></p>").html(msg);  
                                         th.closest("tr").replaceWith(html.find(".text_line"));
                                        
                                         $(".result_line").replaceWith(html.find(".result_line"));
                                        
                                        
                                    }
                        });


                    }

                }
                
    });



});




$(document).on("click", '.dropdown-menu li', function( ) {

    var t = $(this).find("a").html();
    var id = $(this).attr("id");
    var type = $(this).attr("type");

    $(this).closest("td").find("button").attr({id: id}).find("span").html(t);
    $(this).closest("td").next().html(type);



});




