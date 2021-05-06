<?php
namespace XMLManagers;
/**
	 * this class manage  the tickets
	 */
class TicketManager
{
	public $xmlDoc;


	function __construct($path = "xml/tickets.xml")
	{
		$this->xmlDoc = simplexml_load_file($path);
		//print_r(json_encode($this->xmlDoc));
	}

	/**
         * retrieve aticketrelated to an id provided
         * @return a SimpleXMLElement of  a ticket's information if exist , null  ortherwise.
         */
	public function findTicket($id='')
	{
		$xpath_request = "/tickets/ticket[id='". $id . "']";
		//echo $xpath_request;
		$results = $this->xmlDoc->xpath($xpath_request);
		//print_r($results);
		if (!$results) {
			return  null;
		}
		else{



			return $results[0];

		}				

	}


	/**
         * retrieve a user's tickets related to the user's id provided
         * @return array of SimpleXMLElement of  tickets' information if exist , empty array ortherwise.
         */
	public function getUserTickets($user_id='')
	{
		$xpath_request = "/tickets/ticket[issuerId='". $user_id . "']";
		//echo $xpath_request;
		$results = $this->xmlDoc->xpath($xpath_request);
		//print_r($results);
		if (!$results) {
			return  array();
		}
		else{



			return $results;

		}				

	}

	/**
         * retrieve all the tickets in the system
         * @return array of SimpleXMLElement of  all the tickets' information  in the system if exist , empty array ortherwise.
         */
	public function getAllTickets()
	{
		$xpath_request = "//ticket";
		//echo $xpath_request;
		$results = $this->xmlDoc->xpath($xpath_request);
		//print_r($results);
		if (!$results) {
			return  array();
		}
		else{				

			return $results;

		}				

	}




}
?>