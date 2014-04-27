<?php
function domain_exists($email, $record = 'MX')
{
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, $record);
}

// Email auf korrektes Format prüfen
function checkmail( $email )
{
    $nonascii      = "\x80-\xff";    
    $nqtext        = "[^\\\\$nonascii\015\012\"]";
    $qchar         = "\\\\[^$nonascii]";
    $normuser      = '[a-zA-Z0-9][a-zA-Z0-9_.-]*';
    $quotedstring  = "\"(?:$nqtext|$qchar)+\"";
    $user_part     = "(?:$normuser|$quotedstring)";
    $dom_mainpart  = '[a-zA-Z0-9][a-zA-Z0-9._-]*\\.';
    $dom_subpart   = '(?:[a-zA-Z0-9][a-zA-Z0-9._-]*\\.)*';
    $dom_tldpart   = '[a-zA-Z]{2,5}';
    $domain_part   = "$dom_subpart$dom_mainpart$dom_tldpart";
    $pattern       = "$user_part\@$domain_part";
    $muster_email  = "/^{$pattern}$/";

    if (preg_match( $muster_email, $email ))
    {
        return $email;
    }
    else
    {
        echo 'The mailaddress is malformed!<br>';
    }
}

function checkInjection()
{
    $email_injection = array( 'bcc:', 'boundary', 'cc:', 'content-transfer-encoding:', 'content-type:', 'mime-version:', 'subject:' );
 
    // Auf potentielle Email Injections prüfen
    foreach ($email_injection as $injection)
    {
        foreach ($_POST as $feld => $inhalt)
        {
            if (preg_match( "/{$injection}/i", $inhalt ))
            {
                header( 'location: http://www.google.com/search?hl=en&q=how+to+become+a+better+hacker' );
                exit;
            }
        }
    }
    return true;
}

function sendmail($email,$login,$subject,$mailbody)
{
    checkInjection();

    // Mail Header
    /*$mailheader  = "From: ProRepo Webserver<noreply@" .$_SERVER['SERVER_NAME']. ">\r\n";
    $mailheader .= "Reply-To: " .$login. "<" .$email. ">\r\n";
    $mailheader .= "Return-Path: noreply@" .$_SERVER['SERVER_NAME']. "\r\n";*/
    $mailheader = "MIME-Version: 1.0\r\n";
    $mailheader .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $mailheader .= "Content-Transfer-Encoding: 7bit\r\n";
/*    $mailheader .= "Message-ID: <" .time(). " noreply@" .$_SERVER['SERVER_NAME']. ">\r\n";
    $mailheader = "X-Mailer: PHP v" .phpversion(). "\r\n\r\n";*/

    // send mail
    if (@mail( $email, $subject, $mailbody, $mailheader ))
    {
        return true;
    }
    else
    {   
        return false;
    }
}

function sendregmail($login,$uniqid,$email)
{
    checkInjection();

    // Load mailtemplate
    $mailbody = file_get_contents( 'includes/mailtemplates/register.txt' );

    $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']."?show=verify&id=".$uniqid;

    $subject = "ProRepo - Welcome to the business process model and log repository.";

    // replace placeholders with given parameters
    $mailbody = str_replace( '###LOGIN###', $login, $mailbody );
    $mailbody = str_replace( '###EMAIL###', $email, $mailbody );
    $mailbody = str_replace( '###URL###', $url, $mailbody );

    return sendmail($email,$login,$subject,$mailbody);
}

function sendmailpw($email,$login,$uniqid)
{
    checkInjection();

    // Load mailtemplate
    $mailbody = file_get_contents( 'includes/mailtemplates/pw_forgot.txt' );

    $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']."?show=userpwreset&id=".$uniqid;

    $subject = "ProRepo - Reset your password ?";

    // replace placeholders with given parameters
    $mailbody = str_replace( '###LOGIN###', $login, $mailbody );
    $mailbody = str_replace( '###URL###', $url, $mailbody );

    sendmail($email,$login,$subject,$mailbody);
}

function sendadminmail()
{
    checkInjection();

    if(isset($_POST['email']))
        $email = checkmail($_POST['email']);

    if(isset($_POST['groupid']))
        $groupid = cleaninput($_POST['groupid']);

    if(isset($_POST['groupid']))
        $subject = "ProRepo Administrator - ".cleaninput($_POST['reason'])." (ID: ".$groupid.")";
    else
        $subject = "ProRepo Administrator - ".cleaninput($_POST['reason']);

    $login = cleaninput($_POST['login']);
    $message = cleaninput($_POST['message']); 
    $timestamp = date("H:i:s - d.m.Y", strtotime($_POST['timestamp']));
    
    // Load mailtemplate
    $mailbody = file_get_contents( 'includes/mailtemplates/admin_contact.txt' );

    // replace placeholders with given parameters
    $mailbody = str_replace( '###LOGIN###', $login, $mailbody );
    $mailbody = str_replace( '###EMAIL###', $email, $mailbody );
    $mailbody = str_replace( '###TIME###', $timestamp, $mailbody );
    $mailbody = str_replace( '###SUBJECT###', $subject, $mailbody );
    $mailbody = str_replace( '###MSG###', $message, $mailbody );

    sendmail(ADMINMAIL,$login,$subject,$mailbody);
}


?>
