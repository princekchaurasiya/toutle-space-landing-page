<?php

if(empty($_POST['namef2'])     ||
   empty($_POST['phonef2']))
	{
    echo "No arguments Provided!";
    return false;
  }

$name = rawurlencode(strip_tags(htmlspecialchars_decode($_POST['namef2'])));

$phone = rawurlencode(strip_tags(htmlspecialchars_decode("+91".$_POST['phonef2'])));

$formid = "PopUpForm";
$qs = strip_tags(htmlspecialchars_decode($_POST['qsf2']));

$formq = "name=".$name."&phone=".$phone."&formid=".$formid."&".$qs;

$link = "https://hook.eu2.make.com/xee231oalbl0s5w87b8alyqvfhpa4ean?";

$flowq = $link.$formq;

function flowtrig($flowq)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $flowq);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);
  $response = curl_exec($ch);
  curl_close($ch);

  if(strpos( $response, "Accepted" ) !== false)
  {
    header('Location: ./thank-you.php');
    exit();
  }
  else
  {
    header('Location: ./form-submit-failed.php');
    exit();
  }
}
flowtrig($flowq);

?>