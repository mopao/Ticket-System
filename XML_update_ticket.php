<?php

//add a new message to a ticket
if(isset($_POST['data']) && isset($_POST['ticket_id'])){

	$xml_doc = new DOMDocument();		
	$xml_doc->load('xml/tickets.xml', LIBXML_NOBLANKS);
	$xml_ticket = null;
	foreach ($xml_doc->documentElement->getElementsByTagName('ticket') as $elt) {
		if ($elt->getElementsByTagName('id')->item(0)->nodeValue === $_POST['ticket_id']) {
			$xml_ticket = $elt;
			break;
		}
	}
	$xml_support_messages = ($xml_ticket->getElementsByTagName('supportMessages')->length !== 0)? $xml_ticket->getElementsByTagName('supportMessages')->item(0) : $xml_doc->createElement('supportMessages');
	//var_dump($xml_support_messages);

	$d= new DOMDocument();
	$d->loadXML($_POST['data']);
	$m = $d->getElementsByTagName('message')->item(0);
	$msg_elt = $xml_doc->createElement('message');

	$attr = $xml_doc->createAttribute('sender');
	// Value for the created attribute
	$attr->value = $m->attributes->getNamedItem("sender")->nodeValue;
	$msg_elt->appendChild($attr);

	date_default_timezone_set("America/Toronto");		
	$date = new DateTime();
	$datetime_elt = $xml_doc->createElement('sendingDateTime',$date->format('Y-m-d\TH:i:s'));		
	$msg_elt->appendChild($datetime_elt);

	$content_elt = $xml_doc->createElement('content', $m->getElementsByTagName('content')->item(0)->nodeValue);		
	$msg_elt->appendChild($content_elt);

	$xml_support_messages->appendChild($msg_elt);
	//var_dump($xml_support_messages);
	if($xml_ticket->getElementsByTagName('supportMessages')->length == 0){
		$xml_ticket->appendChild($xml_support_messages);
	}

	$xml_doc->preserveWhiteSpace = true;
	$xml_doc->formatOutput = true;
	//echo $xml_doc->saveXML();
	$xml_doc->save('xml/tickets.xml');

	//echo "Element saved";

}
//update a ticket's status
elseif (isset($_POST['ticket_status']) && isset($_POST['ticket_id'])) {
	# code...
	$xml_doc = new DOMDocument();		
	$xml_doc->load('xml/tickets.xml', LIBXML_NOBLANKS);
	$xml_ticket = null;
	foreach ($xml_doc->getElementsByTagName('ticket') as $elt) {
		if ($elt->getElementsByTagName('id')->item(0)->nodeValue === $_POST['ticket_id']) {
			$xml_ticket = $elt;
			break;
		}
	}

	$attr = $xml_ticket->attributes->getNamedItem('status');
	$attr->nodeValue = $_POST['ticket_status'];

	if ($_POST['ticket_status'] === "closed") {	
		date_default_timezone_set("America/Toronto");		
		$date = new DateTime();
		$closing_date_elt = $xml_doc->createElement('closureDateTime',$date->format('Y-m-d\TH:i:s'));
		$subject_elt = $xml_ticket->getElementsByTagName('subject')->item(0);
		echo $subject_elt->nodeName;
		$subject_elt->parentNode->insertBefore($closing_date_elt, $subject_elt);
	}
	$xml_doc->preserveWhiteSpace = true;
	$xml_doc->formatOutput = true;
	//echo $xml_doc->saveXML();
	$xml_doc->save('xml/tickets.xml');
	// redirect to the ticket details  page
	header("Location: ticket_details.php?ticket=" . $_POST['ticket_id']);
	exit;
}
// create a new ticket
elseif (isset($_POST['create_ticket']) ) {

	if ($_POST['subject'] !== "" && $_POST['description'] !== "") {

		# code...
		$xml_doc = new DOMDocument();		
		$xml_doc->load('xml/tickets.xml', LIBXML_NOBLANKS);
		$xml_ticket = $xml_doc->createElement('ticket');

		//create attribute status 
		$attr_status = $xml_doc->createAttribute('status');
		$attr_status->nodeValue = "opened";

		//create the ticket id element
		$lastchild = $xml_doc->documentElement->lastChild;	       
		$last_id = intval($lastchild->getElementsByTagName('id')->item(0)->nodeValue);
		$new_id = $last_id +1;
		$new_str_id = '' . $new_id;
		$id_len = strlen($new_str_id);	         
		$len = 4 - $id_len ;
		for ($i=1; $i <= $len; $i=$i+1) { 
			$new_str_id = '0' . $new_str_id;
		}

		$id_elt = $xml_doc->createElement('id', $new_str_id);

		//create the issuer id element
		$issuer_id_elt = $xml_doc->createElement('issuerId',$_POST['user_id']);

		//create the issued datetime element
		date_default_timezone_set("America/Toronto");
		$date = new DateTime();
		$datetime_elt = $xml_doc->createElement('issueDateTime',$date->format('Y-m-d\TH:i:s'));

		//create the subject element 
		$subject_elt = $xml_doc->createElement('subject',trim($_POST['subject']));

		//create description element
		$description_elt = $xml_doc->createElement('description',trim($_POST['description']));

		//add all the ticket elements
		$xml_ticket->appendChild($attr_status);
		$xml_ticket->appendChild($id_elt);
		$xml_ticket->appendChild($issuer_id_elt);
		$xml_ticket->appendChild($datetime_elt);
		$xml_ticket->appendChild($subject_elt);
		$xml_ticket->appendChild($description_elt);

		// add the new ticket into the dom 
		$xml_doc->documentElement->appendChild($xml_ticket);

		// save the dom in a file
		$xml_doc->preserveWhiteSpace = true;
		$xml_doc->formatOutput = true;
		//echo $xml_doc->saveXML();
		$xml_doc->save('xml/tickets.xml');


	}

	// redirect to the list page
	header("Location: ticket_list.php");
	exit;
}
?>