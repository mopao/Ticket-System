<?php    
use XMLManagers\{UserManager,TicketManager};
require __DIR__ . '/vendor/autoload.php';


if (isset($_POST['format']) && $_POST['format']==='html' && isset($_POST['ticket_id'])) {

    $ticketManager = new TicketManager(); 
    $userManager = new UserManager();   
    $ticket = $ticketManager->findTicket($_POST['ticket_id']);
    $html_messsages = "";
    if(isset($ticket->supportMessages)){
        $issuer_id = $ticket->issuerId->__toString();
        foreach ($ticket->supportMessages->children() as $message) {
            $sender_id = $message->attributes()->sender->__toString();
            $sender = $userManager->findUserById($sender_id);
            $html_messsages .= '<div class="';
            $html_messsages .= ($issuer_id === $sender_id)? 'message-box-c' : 'message-box-s';
            $html_messsages .= '">';
            $html_messsages .= '<h3><span class="msg-author">'. $sender->name->firstName->__toString() . ' ' . $sender->name->lastName->__toString() . '</span> [ <span class="msg-date">';
            $date = DateTime::createFromFormat("Y-m-d\TH:i:s", $message->sendingDateTime->__toString());               
            $html_messsages .= $date->format('Y-m-d H:i:s') . '</span>]</h3>' . $message->content->__toString() . '</div>';
        }
    }



}

echo $html_messsages;



?>