jQuery(document).ready(function($) {

    const endpoint = 'https://api.chucknorris.io/jokes/categories';
    $.ajax({
        url: endpoint,
        contentType: 'application/json',
        dataType: 'json',
        // Append api categories.
        success: function(result) {
            $.each(result, function (i, item) {
                $('#appendCategories').append($('<option>', {
                    value: item,
                    text : item
                }));
            });
        },
        // And then put option selected.
        complete: function () {
            $.ajax({
                url:object_ajax.ajax,
                type:'POST',
                data: $(this).serialize() + "&action=get_category_api",
                success:function(response) {
                    console.log(response);
                    $('#appendCategories').val(response);
                },
            });
        },
    });
    // On change run backend function.
    $("#appendCategories").on('change', function(event){
        event.preventDefault();
        $.ajax({
            url:object_ajax.ajax,
            type:'POST',
            data: $(this).serialize() + "&action=create_or_update", // wp_ajax_create_or_update
            success:function(response){
                console.log(response)
            },
        });
    });

});