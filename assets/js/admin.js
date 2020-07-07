/* global jQuery */
(function ($) {
    $(function () {
        // Show confirm message when a delete link is clicked.
        $('.confirm-required').on('click', function (e) {
            var message = '정말로 삭제할까요?',
                target = e.target;

            if (target.dataset.hasOwnProperty('confirmMessage') && 'string' === typeof target.dataset.confirmMessage && target.dataset.confirmMessage.length > 0) {
                message = target.dataset.confirmMessage;
            }

            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });
})(jQuery);
