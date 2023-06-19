(function($) {
  $.fn.placeShortcut = function(shortcut_array, options)
    {
        if (shortcut_array.length !== 0)
        {
            var settings = $.extend({
                // These are the defaults.
                shortcut: $(this).attr("shortcut") || true,
                display_shortcut: $(this).attr("display_shortcut") || true,
                hilight_shortcut: $(this).attr("hilight_shortcut") || true
            }, options);
            var count = 0;
            var sub_count = 0;

            $(this).find(".apply-shortcut").each(function() {
                if ($(this).children().is('ul'))
                {
                    if (shortcut_array[count] !== undefined)
                    {
                        if (shortcut_array[count][sub_count] !== undefined)
                        {
                            $(this).children().find("li").each(function() {
                                $(this).find("a").attr("shortcut_key", shortcut_array[count][sub_count]);
                                sub_count++;
                            });
                        }
                        else
                        {
                            alert('Your array does not match with menu');
                        }
                    }
                    count++;
                    sub_count = 0;
                }
                else if ($(this).children().is('a') === true) {
                    {
                        if ($(this).parent().is("ul .dropdown-menu") === false)
                        {
                            $(this).find("a").attr("shortcut_key", shortcut_array[count]);
                            count++;
                            sub_count = 0;
                        }
                    }
                }
            });
            this.attr({
                shortcut: settings.shortcut,
                display_shortcut: settings.display_shortcut,
                hilight_shortcut: settings.hilight_shortcut
            });
        }
        if ($(this).attr("shortcut") === "true")
        {
            highlightShortcut($(this));
            displayShortcut($(this));
            $(document).on("keyup", keyHandler);
        }
    };
    var keyHandler = function(e) {
        var keySpecial = new Array();
        var counter = 0;

        if (e.shiftKey === true) {
            keySpecial[counter] = "shift";
            counter++;
        }
        if (e.ctrlKey === true)
        {
            keySpecial[counter] = "ctrl";
            counter++;
        }
        if (e.altKey === true)
        {
            keySpecial[counter] = "alt";
            counter++;
        }
        $(this).find(".apply-shortcut").each(function() {

            var given_key = $(this).attr("shortcut_key");

            if (given_key !== undefined)
            {
                var final_key = given_key.split("+");
                var len = final_key.length;
                var key = final_key[len - 1];
                final_key.splice(-1, 1);
                final_key = final_key.sort();
                keySpecial = keySpecial.sort();
                var is_same = keySpecial.length === final_key.length && keySpecial.every(function(element, index) {
                    return element === final_key[index];
                });
                
                if (key === String.fromCharCode(e.which).toLowerCase() && is_same) {
                    $(this)[0].click();                    
                    $(this).next('ul').find("li:first").find("a").focus();                    
                    $(this).closest(".dropdown").addClass('open');
                    $("li.dropdown a").each(function() {
                         $(this).removeClass("active");
                    })
                    $(this).addClass("active");
                    that = this;
                    /*setTimeout(function() {
                        $(that).removeClass("active");
                        $(that).closest(".dropdown").removeClass('open');
                    }, 500);*/
                    e.preventDefault();
                }
            }
        });
        };
}(jQuery));
function highlightShortcut(tag)
{
    if ($(tag).attr("hilight_shortcut") === "true" && $(tag).attr("shortcut") === "true")
    {
        $('.shortcut-main').find(".apply-shortcut").each(function() {
            var given_key = $(this).attr("shortcut_key");
            if (given_key !== undefined)
            {
                var text = $(this)[0].innerHTML;
                var split_given_key = given_key.split("+");
                var text_length = split_given_key.length;
                if (text.indexOf(split_given_key[text_length - 1]) > -1)
                {
                    var new_char = "<span class='hilight'>" + split_given_key[text_length - 1] + "</span>";
                    var new_text ="<span style='float: left; display: inline-block;'>"+ text.replace(split_given_key[text_length - 1], new_char)+"</span> ";
                    $(this).text('');
                    $(this).append(new_text);
                }
            }
        });
    }
};
function displayShortcut(tag)
{
    if ($(tag).attr("display_shortcut") === "true" && $(tag).attr("shortcut") === "true")
    {
        $('.shortcut-main').find(".apply-shortcut").each(function() {
            if ($(this).attr("shortcut_key") !== undefined)
            {
                var given_key = "<span class='pull-right'>" + $(this).attr("shortcut_key") + "</span> <div class='clearfix'></div>";
                $(this).append(given_key);
            }
        });
    }
};
/* Keydown For left side Menu Navigation */
$(document).keydown(function(event) {
    var key = event.which;
    
    if ($(".nav-side-menu ul .sub-menu li a").is(":focus")) {        
        if(key === 40) {                              
            var $focused = $(':focus');
            $focused.closest( 'li' ).next('li').find('a').focus();            
        }                
        if (key === 38) {      
            var $focused = $(':focus');
            $focused.closest( 'li' ).prev('li').find('a').focus();        
        }
    }
    /*if ((event.ctrlKey) && (event.altKey) && (key === 79)) {
        $('#OrganizationConfirmModal').modal('show'); 
    }*/
});