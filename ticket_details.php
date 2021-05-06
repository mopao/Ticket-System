<?php
session_start();
date_default_timezone_set("America/New_York");

use XMLManagers\{UserManager,TicketManager};
require __DIR__ . '/vendor/autoload.php';

$userManager = new UserManager();
$ticketManager = new TicketManager();
$user = $userManager->findUser($_SESSION['user']['username'],$_SESSION['user']['password']);
$ticket = $ticketManager->findTicket($_GET['ticket']);



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="imgs/favicon-3.ico" >
    <title> Ticket system - Ticket Details  </title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/ticket_details.css">

  </head>
  <body>

    <nav class="container"> <a href="ticket_list.php"> Back to the list of tickets</a></nav>
    <main class="container">
      <h1>Ticket Details </h1>

      <div class="table-responsive">
        <table class="table table-hover table-bordered ">

          <tbody>
            <tr>
              <th scope="row" class=" text-white bg-dark"> Ticket#:</th>
              <td>
                <?php
                echo $_GET['ticket'];
                ?>
              </td>
            </tr>
            <tr>
              <th scope="row" class=" text-white bg-dark"> Issued On:</th>
              <td>
                <?php
                $date = DateTime::createFromFormat("Y-m-d\TH:i:s", $ticket->issueDateTime->__toString());
                echo $date->format('M d Y ') . 'at ' . $date->format('H:i a');
                ?>
              </td>
            </tr>
            <tr>
              <th scope="row" class=" text-white bg-dark">closed on:</th>
              <td>
                <?php
                if(!empty($ticket->closureDateTime)){

                  $date = DateTime::createFromFormat("Y-m-d\TH:i:s", $ticket->closureDateTime->__toString());
                  echo $date->format('M d Y ') . 'at ' . $date->format('H:i a');
                }
                ?>
              </td>
            </tr>
            <tr>
              <th scope="row" class=" text-white bg-dark"> status:</th>
              <td><?= $ticket->attributes()->status->__toString();?></td>
            </tr>
            <tr>
              <th scope="row" class=" text-white bg-dark"> issued by:</th>
              <td>
                <?php

                $issuer = $userManager->findUserById($ticket->issuerId->__toString());
                echo $issuer->name->firstName->__toString() . ' ' . $issuer->name->lastName->__toString();
                ?>
              </td>
            </tr>
            <tr>
              <th scope="row" class=" text-white bg-dark"> subject:</th>
              <td><?= $ticket->subject->__toString();?></td>
            </tr>
            <tr>
              <th scope="row" class=" text-white bg-dark"> Description:</th>
              <td>
                <?= $ticket->description->__toString();?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <?php
      if ($user->attributes()->type->__toString() === "staff" && $ticket->attributes()->status->__toString() !== "closed" ) {
      ?>
      <form action="XML_update_ticket.php" method="post">
        <h2>Change ticket status</h2>
        <input type="hidden" name="ticket_id" id="ticket_id" value="<?= $_GET['ticket'] ?>">
        <select name="ticket_status">
          <option value=""></option>
          <option value="in-process">In-Process</option>
          <option value="closed">Closed</option>
          <input type="submit" name="update_status" value="Update Status" id="update_status">
        </select>
      </form>
      <?php
      }
      ?>
      <section >
        <h2>Messages</h2>
        <div id="messages">
          <?php
          if(isset($ticket->supportMessages)){
            $issuer_id = $ticket->issuerId->__toString();
            foreach ($ticket->supportMessages->children() as $message) {
              $sender_id = $message->attributes()->sender->__toString();
              $sender = $userManager->findUserById($sender_id);
          ?>
          <div class="<?=  ($issuer_id === $sender_id)? 'message-box-c' : 'message-box-s'?>">
            <h3><span class="msg-author"><?= $sender->name->firstName->__toString() . ' ' . $sender->name->lastName->__toString();?></span> [
              <span class="msg-date">
                <?php
              $date = DateTime::createFromFormat("Y-m-d\TH:i:s", $message->sendingDateTime->__toString());
              echo $date->format('Y-m-d H:i:s');
                ?>
              </span>
              ]</h3>

            <?= $message->content->__toString(); ?>
          </div>
          <?php
            }
          }
          ?>
        </div>
      </section>
      <?php
      if($ticket->attributes()->status->__toString() !== "closed"){
      ?>
      <form action="" method="Post" name="f_ticket_chat">
        <h2>Send a message</h2>
        <input type="hidden" name="ticket_id" id="ticket_id" value="<?= $_GET['ticket'] ?>">
        <input type="hidden" name="sender_id" id="sender_id" value="<?= $user->id->__toString() ?>">
        <div>
          <textarea cols="80" rows="6" placeholder="Enter your message here." id="ticket_msg"></textarea>
        </div>
        <div>
          <input type="submit" name="send" value="Send" >
        </div>
      </form>
      <script type="text/javascript" src="js/ticket_details.js"></script>
      <?php
      }
      ?>
    </main>

  </body>
</html>
