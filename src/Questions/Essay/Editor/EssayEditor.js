(function ($) {
    let textLength;
    let maxLength;
    let hasMaxLength = false;

    function updateCounts() {
        if (typeof (tinymce) === 'undefined') {
            textLength = $('js_essay').val().length;
        }
        else {
            const body = tinymce.editors[0].getBody();
            const text = tinymce.trim(body.innerText || body.textContent);
            textLength = text.length;            
        }

        $('.js_letter_count').html(textLength);
    }

    function checkValues() {
        if (hasMaxLength) {
            if (textLength > maxLength) {
                // TODO use ilias modalpopup
                alert($('.js_error').val());
                return false;
            }
        }

        return true;
    }

    $(document).on('keyup', '.js_essay', updateCounts);
    $(document).on('submit', 'main form', checkValues);

    $(document).ready(() => {
        if (typeof (tinymce) === 'undefined') {
            return;
        }

        tinymce.init({
            selector: 'textarea',
            menubar: false,
            init_instance_callback(editor) {
                editor.on('keyup', updateCounts);
                updateCounts();
            },
        });

        if ($('.js_maxlength').length > 0) {
            maxLength = parseInt($('.js_maxlength').val(), 10);
            hasMaxLength = true;
        }
    });
}(jQuery));
