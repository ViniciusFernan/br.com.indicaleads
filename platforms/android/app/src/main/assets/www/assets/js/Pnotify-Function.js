/**
 * Notificação do pNotify
 * @param {string} title
 * @param {string} text
 * @param {string} type
 * @param {boolean} hide
 */
function notify(title, text, type, hide) {
    if (!type)
        type = 'info';
    if (!hide)
        hide = false;
    var notice = new PNotify({
        title: title,
        text: text,
        type: type,
        hide: hide,
        addclass: "notify_db"
    });
    notice.get().click(function () {
        notice.remove();
    });
}