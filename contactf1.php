<?php

// Enable error reporting for debugging during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log file location
$logFile = './form_errors.log';

// Helper function for logging
function logMessage($message, $logFile) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
}

// Validate required fields
if (empty($_POST['namef1']) || empty($_POST['phonef1'])) {
    logMessage("Missing required fields: name or phone.", $logFile);
    echo "No arguments Provided!";
    return false;
}

// Sanitize and process form inputs
$name = rawurlencode(strip_tags(htmlspecialchars_decode($_POST['namef1'])));
$email = rawurlencode(strip_tags(htmlspecialchars_decode($_POST['emailf1'] ?? ''))); // Handle missing email gracefully
$phone = rawurlencode(strip_tags(htmlspecialchars_decode("+91" . $_POST['phonef1'])));
$formid = "HeaderForm";
$qs = strip_tags(htmlspecialchars_decode($_POST['qsf1'] ?? ''));

// Prepare query string
$formq = "name=" . $name . "&email=" . $email . "&phone=" . $phone . "&formid=" . $formid . "&" . $qs;

// Choose webhook URL
$pipedriveLink = "https://hook.eu2.make.com/xee231oalbl0s5w87b8alyqvfhpa4ean";
// $zohoLink = "https://hook.eu2.make.com/29ru205lfw99y6yk3amixkx0dshsd8ou";
$link = $pipedriveLink; // Use the desired webhook URL here
$flowq = $link . "?" . $formq;

// Function to trigger the flow
function flowtrig($flowq, $logFile) {
    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $flowq);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);

    // Execute cURL request
    $response = curl_exec($ch);
    $error = curl_error($ch); // Capture CURL errors
    curl_close($ch);

    // Log CURL errors or response
    if ($error) {
        logMessage("CURL Error: $error", $logFile);
    } else {
        logMessage("Response: $response", $logFile);
    }

    // Handle response
    if (strpos($response, "Accepted") !== false) {
        logMessage("Flow Trigger Successful. Redirecting to thank-you.php", $logFile);
        header('Location: ./thank-you.php');
        exit();
    } else {
        logMessage("Flow Trigger Failed. Redirecting to form-submit-failed.php", $logFile);
        header('Location: ./form-submit-failed.php');
        exit();
    }
}

// Trigger the flow
flowtrig($flowq, $logFile);

?>
