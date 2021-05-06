<?php
session_start();

use XMLManagers\{UserManager,TicketManager};
require __DIR__ . '/vendor/autoload.php';
$tickets = array();
$userManager = new UserManager();
$ticketManager = new TicketManager();
$user = $userManager->findUser($_SESSION['user']['username'],$_SESSION['user']['password']);
if($user->attributes()->type->__toString() === 'client')
  $tickets = $ticketManager->getUserTickets($user->id);
else
  $tickets = $ticketManager->getAllTickets();

date_default_timezone_set("America/New_York");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="imgs/favicon-3.ico" >
    <title> Ticket system - List  </title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/ticket_list.css">

  </head>
  <body class="container">
    <nav> <a href="index.php?page=logout"> Log Out</a></nav>
    <main >
      <h1> List of Tickets  </h1>

      <div class="table-responsive">
        <table class="table table-hover table-sm table-dark">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Ticket#</th>
              <th scope="col">Issued On</th>
              <?php
              if ($user->attributes()->type->__toString() === 'staff'){
              ?>
              <th scope="col">issued by</th>
              <?php
              }
              ?>
              <th scope="col">subject</th>
              <th scope="col">status</th>
              <th scope="col">closed on</th>
            </tr>
          </thead>
          <tbody>

            <?php
            foreach ($tickets as $ticket) {
            ?>
            <tr class="clickable-row" >
              <th scope="row"><?= $ticket->id->__toString();?></th>
              <td>
                <?php
              $date = DateTime::createFromFormat("Y-m-d\TH:i:s", $ticket->issueDateTime->__toString());
              echo $date->format('M d Y ') . 'at ' . $date->format('H:i a');
                ?>
              </td>
              <?php
              if ($user->attributes()->type->__toString() === 'staff'){

                $issuer = $userManager->findUserById($ticket->issuerId->__toString());
              ?>
              <td><?= $issuer->name->firstName->__toString() . ' ' . $issuer->name->lastName->__toString() ;?></td>
              <?php
              }
              ?>
              <td><?= $ticket->subject->__toString();?></td>
              <td><?= $ticket->attributes()->status->__toString();?></td>
              <td>
                <?php
              if(!empty($ticket->closureDateTime)){

                $date = DateTime::createFromFormat("Y-m-d\TH:i:s", $ticket->closureDateTime->__toString());
                echo $date->format('M d Y ') . 'at ' . $date->format('H:i a');
              }
                ?>
              </td>
          </a>
          </tr>
        <?php
            }
        ?>

        </tbody>
      </table>
    </div>

  <?php
  if ($user->attributes()->type->__toString() === "client") {
  ?>
  <form action="XML_update_ticket.php" method="post" class="container">
    <h2>Create a ticket</h2>
    <input type="hidden" name="user_id" value="<?= $user->id->__toString()?>">
    <div class="form-row">
      <label class="col-md-2">Subject:</label>
      <select  name="subject">
        <option value=""></option>
        <option value="billing mistake">Billing mistake</option>
        <option value="power outage">Power outage</option>
        <option value="subscription cancellation">Subscription cancellation</option>
        <option value="subscription upgrade">Subscription upgrade</option>
      </select>
    </div>
    <div>
      <label>Description:</label>
    </div>
    <div>
      <textarea name="description" cols="10" rows="5"  placeholder="Enter your text here." ></textarea>
    </div>
    <div>
      <input type="submit" name="create_ticket" value="Create ticket">
    </div>
  </form>
  <?php
  }
  ?>
  </main>

<script type="text/javascript" src="js/ticket_list.js"></script>
</body>
</html>
