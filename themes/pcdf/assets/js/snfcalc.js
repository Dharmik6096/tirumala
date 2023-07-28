$(document).ready(function () {
    /* SNF CALC */
    // Get all the keys from document
    var keys = document.querySelectorAll('#calculator span');
    var operators = ['+', '-', '*', '/'];
    var numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    var fat = {
        'FAT': '$',
        'FATKG': '#',
        'SNF': '@',
        'SNFKG': '&',
        'TS': '<',
        'TSKG': '>',
        'CLR': '~',
        'AMT': '^',
        'AMOUNT': '^',
        'QTY': '^',
        'KGFAT': '^',
        'KGSNF': '^',
        '[val0]': '^',
        '[val1]': '^',
        '[val2]': '^',
        '[val3]': '^',
        '[val4]': '^',
        '[val5]': '^',
        '[val6]': '^',
        '[val7]': '^',
        '[val8]': '^',
        '[val9]': '^',
        'DAYS': '!',
        'PDAYS': '%',
    };
    //var fat={'FAT'=>'$','FATKG'=>'#','SNF'=>'@','SNFKG'=>'&'};
    var decimalAdded = false;

// Add onclick event to all the keys and perform operations
    for (var i = 0; i < keys.length; i++) {
        keys[i].onclick = function (e) {
            // Get the input and button values
            var input = document.querySelector('.screen > span');

            //var hdninput = $('#hdn_screen');

            var inputVal = input.innerHTML;
            var btnVal = this.innerHTML;
            var lastChar = $('#formula').val()[$('#formula').val().length - 1];

            // Now, just append the key values (btnValue) to the input string and finally use javascript's eval function to get the result
            // If clear key is pressed, erase everything
            if (btnVal == 'C') {
                input.innerHTML = '';
                $('#formula').val('');
                decimalAdded = true;
            }
//            else if (btnVal == 'B') {
//                var len = 0;
//                var text = input.innerHTML;
//                if (text != '') {
//                    var sp = text[text.length - 1].match(/([a-zA-Z])+/g);
//                    if (sp != null) {
//                        var str = text.replace(/([\-~@!#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, ' ');
//                        str = str.split(' ').reverse()[0];
//                        len = input.innerHTML.length - str.length;
//                    } else {
//                        len = input.innerHTML.length - 1;
//                    }
//                    var modified = text.substr(0, len);
//                    input.innerHTML = modified;
//                    $('#formula_description').val(modified);
//                    decimalAdded = true;
//                }
//            }

            // If backspace key is pressed, erase last character
            else if (btnVal == 'Back' || btnVal == 'B') {
                var str = $('#formula').val();
                $('#formula').val(str.substring(0, str.length - 1));
                input.innerHTML = $('#formula').val();
                var re = $.map(fat, function (v, k) {
                    return {
                        key: k,
                        value: v
                    };
                });
                $(input).text(function (i, val) {
                    $.each(re, function (i, obj) {
                        val = val.replace(obj.value, obj.key);
                    });
                    return val;
                });
                console.log($(input).text());
            }

            // If eval key is pressed, calculate and display the result
//            else if (btnVal == '=') {
//                var equation = inputVal;
//                var lastChar = equation[equation.length - 1];
//
//                // Replace all instances of x and ÷ with * and / respectively. This can be done easily using regex and the 'g' tag which will replace all instances of the matched character/substring
//                equation = equation.replace(/x/g, '*').replace(/÷/g, '/');
//
//                // Final thing left to do is checking the last character of the equation. If it's an operator or a decimal, remove it
//                if (operators.indexOf(lastChar) > -1 || lastChar == '.')
//                    equation = equation.replace(/.$/, '');
//
//                if (equation)
//                    input.innerHTML = eval(equation);
//
//                decimalAdded = false;
//            }

            // Basic functionality of the calculator is complete. But there are some problems like
            // 1. No two operators should be added consecutively.
            // 2. The equation shouldn't start from an operator except minus
            // 3. not more than 1 decimal should be there in a number

            // We'll fix these issues using some simple checks

            // indexOf works only in IE9+
            else if (operators.indexOf(btnVal) > -1) {

                // Operator is clicked
                // Get the last character from the equation

                // Only add operator if input is not empty and there is no operator at the last
                if (inputVal != '' && operators.indexOf(lastChar) == -1 && lastChar !== '(')
                {
                    input.innerHTML += btnVal;
                    $('#formula').val($('#formula').val() + btnVal);
                }

                // Allow minus if the string is empty
//                else if (inputVal == '' && btnVal == '-')
//                {
//                    input.innerHTML += btnVal;
//
//                }

                // Replace the last operator (if exists) with the newly pressed operator
                if (operators.indexOf(lastChar) > -1 && inputVal.length > 1) {
                    // Here, '.' matches any character while $ denotes the end of string, so anything (will be an operator in this case) at the end of string will get replaced by new operator
                    input.innerHTML = inputVal.replace(/.$/, btnVal);
                    $('#formula').val($('#formula').val().replace(/.$/, btnVal));
                }
                decimalAdded = false;
            }
            // Now only the decimal problem is left. We can solve it easily using a flag 'decimalAdded' which we'll set once the decimal is added and prevent more decimals to be added once it's set. It will be reset when an operator, eval or clear key is pressed.
            else if (btnVal == '.') {
                if (!decimalAdded) {
                    input.innerHTML += btnVal;
                    decimalAdded = true;
                    $('#formula').val($('#formula').val() + btnVal);
                }
            }
            else if (arrayObjectIndexOf(fat, btnVal) !== -1) {

                if (!getIndexFromVal(fat, lastChar) && numbers.indexOf(lastChar) == -1)
                {
                    input.innerHTML += btnVal;
                    $('#formula').val($('#formula').val() + arrayObjectIndexOf(fat, btnVal));
                }
            }
            else if (numbers.indexOf(btnVal) > -1) {

                if (lastChar !== ')' && !getIndexFromVal(fat, lastChar)) {
                    input.innerHTML += btnVal;
                    $('#formula').val($('#formula').val() + btnVal);
                }
            }
            else if (btnVal == '(') {
//                if (inputVal === '' || operators.indexOf(lastChar) !== -1 || numbers.indexOf(lastChar) !== -1) {
                if (lastChar !== ')') {
                    input.innerHTML += btnVal;
                    $('#formula').val($('#formula').val() + btnVal);
                }
            }
            else if (btnVal == ')') {
                var open = 0, close = 0;
                for (i = 0; i < $('#formula').val().length; i++) {
                    if ($('#formula').val()[i] == '(')
                        open++;
                    else if ($('#formula').val()[i] == ')')
                        close++;
                }
                if (inputVal !== '' && operators.indexOf(lastChar) === -1 && open >= (close + 1)) {
                    input.innerHTML += btnVal;
                    $('#formula').val($('#formula').val() + btnVal);
                }
            }

            // if any other key is pressed, just append it
            else {
                input.innerHTML += btnVal;
                $('#formula').val($('#formula').val() + btnVal);
            }
            $('#formula_description').val(input.innerHTML);
            $('#tblgeneralformula-formula').val(input.innerHTML);
            $('#finalformula > div').text(input.innerHTML);
            $('#tblmccgeneralformula-formula').val(input.innerHTML);
            $('.top .screen').scrollLeft(300);
            // prevent page jumps
            e.preventDefault();
        }
    }
    $(document).keypress(function (e) {
        var keyVal = String.fromCharCode(e.which);
        $("#calculator span").each(function (index) {
            if ($(this).text() == keyVal) {
                $(this).trigger("click");
                return false;
            }
        });
//        switch (keyVal) {
        switch ('') {
            case 'c':
                $('.keys span:nth-child(1)').trigger("click");
                break;
            case 'f':
                $('.keys span:nth-child(2)').trigger("click");
                break;
            case 'F':
                $('.keys span:nth-child(3)').trigger("click");
                break;
            case 's':
                $('.keys span:nth-child(4)').trigger("click");
                break;
            case 'S':
                $('.keys span:nth-child(5)').trigger("click");
                break;
            case 't':
                $('.keys span:nth-child(6)').trigger("click");
                break;
            case 'T':
                $('.keys span:nth-child(7)').trigger("click");
                break;
            default:

        }

    });
    $(document).keyup(function (e) {
        if (e.keyCode == 27 || e.keyCode == 26) {
            $('.clear').trigger("click");
        }
    });
});

function arrayObjectIndexOf(myArray, property) {
    if (myArray.hasOwnProperty(property)) {
        return myArray[property];
    }
    return -1;
}

function getIndexFromVal(myArray, search) {
    for (var prop in myArray) {
        if (myArray.hasOwnProperty(prop) && myArray[prop] === search)
            return true;
    }

    return false;
}
