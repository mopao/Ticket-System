/*JSlint browser: true */
/*global window, alert*/

function goToDetails() {
    "use strict";
    var elt = this;
    var ticket_id = elt.firstElementChild.innerHTML;
    window.location.href = "ticket_details.php?ticket=" + ticket_id;
}
window.onload = function () {
    "use strict";

    var ticket_rows = document.getElementsByClassName('clickable-row');
    var i;
    for (i = 0; i < ticket_rows.length; i += 1) {
        ticket_rows[i].onclick = goToDetails;
    }
};