jQuery(document).ready(function($) {

    let endpoint = '';
    let res = ''
    // Execute the two requests every x time it receives as a callback.
    // The setInterval() method, offered on the Window and Worker interfaces, repeatedly calls a function or executes a code snippet, with a fixed time delay between each call.
    setInterval(function(){
        // Changing the endpoint.
        $.ajax({
            url: object_ajax.ajax,
            type: 'POST',
            data: $(this).serialize() + "&action=get_category_api",
            success: function(response) {
                res = response;
                $('#error').html('No category selected!');
                $('.wpdberror').html('No category selected!');
                $('#category').html(`Current category: ${response}`);
                endpoint = `https://api.chucknorris.io/jokes/random?category=${response}`;
            },
            error: function (response) {
                alert(response);
            }
        });
        // Append jokes to box.
        if (res) {
            $.ajax({
                url: endpoint,
                contentType: 'application/json',
                dataType: 'json',
                success: function({ value, categories }) {
                    $('#appendBox').append(`<h5 class="ml-5"> ${value} <span> ( ${categories} ) </span> </h5>`);
                },
            });
        }

    }, 2000);

});