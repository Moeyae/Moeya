jQuery(document).ready(function($) {
    var container = $('.customize-controls-preview-toggle'),
        resetBtn = $('<input type="submit" name="zaxu-reset" class="zaxu-customizer-reset button-secondary button">').attr('value', _zaxu_customizer_reset.reset);
    resetBtn.on('click', function (event) {
        event.preventDefault();
        var data = {
            wp_customize: 'on',
            action: 'customizer_reset',
            nonce: _zaxu_customizer_reset.nonce.reset
        };
        var r = confirm(_zaxu_customizer_reset.confirm);
        if (!r) return;
        resetBtn.attr('disabled', 'disabled');
        $.post(ajaxurl, data, function() {
            wp.customize.state('saved').set(true);
            location.reload();
        });
    });
    resetBtn.insertBefore(container);
});
