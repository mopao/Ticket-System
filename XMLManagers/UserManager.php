<?php
namespace XMLManagers;
/**
	 * this class manage  the ticket system users
	 */
class UserManager
{
	public $xmlDoc;

	function __construct($path = "xml/users.xml")
	{
		$this->xmlDoc = simplexml_load_file($path);
		//print_r(json_encode($this->xmlDoc));
	}


	/**
         * check if there is an user with the username and password provided
         * @return true if the user exists , false ortherwise.
         */
	public function checkLogin($username='', $password='')
	{

		//echo $password . "<br>";
		//echo $username . "<br>";			

		$xpath_request = "/users/user[username='". $username . "']";
		//echo $xpath_request;
		$results = $this->xmlDoc->xpath($xpath_request);
		//print_r($results);
		if (!$results) {
			return false;
		}
		else{

			$user = $results[0];
			//echo $user->attributes()->type;

			if(password_verify($password, $user->password)){
				return true;
			}

			return false;

		}
	}

	/**
         * retireve a user related to the username and password provided
         * @return SimpleXMLElement of  user's information if the user exists , empty array ortherwise.
         */
	public function findUser($username='', $password='')
	{


		$xpath_request = "/users/user[username='". $username . "']";
		//echo $xpath_request;
		$results = $this->xmlDoc->xpath($xpath_request);
		//print_r($results);
		if (!$results) {
			return array();
		}
		else{

			$user = $results[0];
			//echo $user->attributes()->type;

			if(password_verify($password, $user->password)){

				/*$info_array['type'] = $user->attributes()->type->__toString();
					$info_array['id'] = $user->id;
					$info_array['name'] = $user->name->firstname . $user->name->lastname;
					$info_array['email'] = $user->contact->email;
					$info_array['phone'] = $user->contact->phone;

					return $info_array;
					*/

				return $user;

			}

			return array();

		}
	}

	/**
         * retireve a user related to an id provided
         * @return SimpleXMLElement of  user's information if the user exists , empty array ortherwise.
         */
	public function findUserById($id='')
	{


		$xpath_request = "/users/user[id='". $id. "']";
		//echo $xpath_request;
		$results = $this->xmlDoc->xpath($xpath_request);
		//print_r($results);
		if (!$results) {
			return array();
		}
		else{

			$user = $results[0];
			return $user;				
		}
	}
}
?>