/*JSlint browser: true */
/*global window, XPathResult, XMLSerializer*/

function displayTicketMessages(ticket_id) {
    "use strict";
    let xhr = new XMLHttpRequest();
    //the actual lines to request the XML
    xhr.open("POST", "ticket_messages_format.php"); //request to open file
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.responseType = "text"; //return the response as a DOM tree
    xhr.send("format=html&ticket_id=" + ticket_id); //send the request
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            //console.log(this.responseText);
            document.getElementById('messages').innerHTML = this.responseText;
            document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
        }
    };
}

// this function add a message in the tickets xml file
function addTicketMessage(xmldoc, ticketnum, sender_id, message) {
    "use strict";
    //create a xml element  message with the message
    let new_message_elt = xmldoc.createElement('message');
    new_message_elt.setAttribute('sender', sender_id);
    //create content element
    let text_message_node = xmldoc.createTextNode(message);
    let content_elt = xmldoc.createElement('content');
    content_elt.appendChild(text_message_node);
    new_message_elt.appendChild(content_elt);

    let http = new XMLHttpRequest();
    http.open("POST", 'XML_update_ticket.php', true);
    http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    const serializer = new XMLSerializer();
    const xmlStr = serializer.serializeToString(new_message_elt);
    http.responseType = "text";
    http.send("data=" + xmlStr + "&ticket_id=" + ticketnum);
    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this.responseText);
            displayTicketMessages(ticketnum);
        }
    };

}
// this function process the form
function processForm() {
    "use strict";
    //retrieve the message
    let msg = document.getElementById('ticket_msg').value;

    if (msg !== "") {
        // retrieve ticket's id
        let ticket_id = document.getElementById('ticket_id').value;
        // retrieve the user's id
        let user_id = document.getElementById('sender_id').value;
        // load the ticket xml file
        let xml = new XMLHttpRequest();
        let xmldocument;
        //the actual lines to request the XML
        xml.open("POST", "xml/tickets.xml"); //request to open file
        xml.responseType = "document"; //return the response as a DOM tree
        xml.send(); //send the request
        xml.onload = function () {
            xmldocument = xml.responseXML;
            addTicketMessage(xmldocument, ticket_id, user_id, msg);

        };
        document.getElementById('ticket_msg').value = "";
    }

    //console.log(user_id);

    return false;
}

window.onload = function () {
    "use strict";
    let msg_form = document.forms.f_ticket_chat;
    msg_form.onsubmit = processForm;
    document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;

};