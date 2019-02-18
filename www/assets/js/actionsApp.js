$(function () {
    $('body').on('tap', '.removeCard', function () {
        var $this = $(this).closest('#cardAvisos');
        $($this).removeClass('bounceInDown').addClass('bounceOutUp').delay(1000).queue(function() {
            $($this).remove();
        });
    });
});