<?php
require 'config.php';
$rows = $pdo->query("SELECT * FROM transactions ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>💳 Payment Transactions</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Date</th>
        <th>Status</th>
        <th>Amount (MWK)</th>
        <th>Type</th>
        <th>Reference</th>
        <th>Tx Ref</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['customer_email']) ?></td>
        <td><?= $row['transaction_date'] ?></td>
        <td><?= $row['status'] ?></td>
        <td><?= number_format($row['amount'], 2) ?></td>
        <td><?= $row['type'] ?></td>
        <td><?= $row['reference'] ?></td>
        <td><?= $row['tx_ref'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

