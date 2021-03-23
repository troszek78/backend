/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('./table.js');
const $ = require('jquery');
require('jquery-ui/ui/widgets/autocomplete');
require('jquery-ui/themes/base/core.css');
require('jquery-ui/themes/base/autocomplete.css');
require('jquery-ui/themes/base/theme.css');
require('jquery-ui/themes/base/menu.css');
require('bootstrap');
require('bootstrap/scss/bootstrap.scss');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    console.log ('app.js');
    if ($('input[create-autocomplete="true"]').length > 0) {
        console.log ($('input[create-autocomplete="true"]'));
        let autocompleteInputs = $('input[create-autocomplete="true"]');
        $.each(autocompleteInputs, function () {
            $(this).setAutocomplete();
        });
    }

});


$.fn.setAutocomplete = function () {
    console.log(this);
    let self = this;

    self.url = $(self).data('route');
    let inputId = $(self).data('inputId');
    self.input = $('#' + inputId);
    console.log($(self).data());
    console.log(self.url);
    console.log(inputId);
    console.log(self.input);

    $(self).autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'POST',
                url: self.url,
                dataType: "json",
                data: {
                    name: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function( event, ui ) {
            console.log( ui.item);
            self.input.val(ui.item.id) ;

        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });


};