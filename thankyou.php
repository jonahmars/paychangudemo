<?php
// thankyou.php

if (isset($_GET['tx_ref']) && !empty($_GET['tx_ref'])) {
    $tx_ref = urlencode($_GET['tx_ref']); // Sanitize for safety
    header("Location: verify_transaction.php?tx_ref=$tx_ref");
    exit;
} else {
    // Display error if tx_ref is missing
    echo "<h2 style='color: red; font-family: sans-serif;'>⚠️ Transaction reference missing.</h2>";
    echo "<p><a href='index.html'>Go back</a></p>";
}
?>

