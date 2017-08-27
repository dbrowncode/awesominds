<?php
  require('../../conn.php');
  include('redir-notinstructor.php');

  $inviteCode = sha1(uniqid($_POST["email"], true));

  $query = $dbcon->prepare("INSERT INTO invite (invitecode, email_sentto, c_number_sentby) VALUES (:invitecode, :email_sentto, :c_number_sentby)");

  $query->bindParam(':invitecode', $inviteCode);
  $query->bindParam(':email_sentto', $_POST["email"]);
  $query->bindParam(':c_number_sentby', $_SESSION["c_number"]);

  $result = $query->execute();

  if($result){
    $subject = 'Account Verification (Awesominds)';
    $headers = "From: Awesominds Registration <noreply@gbl.cs.camosun.bc.ca>" . "\r\n" .
               "Reply-To: noreply@gbl.cs.camosun.bc.ca" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();
    $message_body = 'You have been invited to create an Awesominds instructor account!

                    Please click this link to create your account:

                    http://gbl.cs.camosun.bc.ca/awesominds/signup.php?invitecode='.$inviteCode;

    mail( $_POST["email"], $subject, $message_body, $headers );
    
    $output = $query->rowCount();
    echo json_encode($output);

  } else {
    echo json_encode($query->errorInfo());
  }
?>
