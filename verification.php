<?php

require_once '../config.php';

$db = new DBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verificationCode = $_POST['verificationCode'];

    $sql = "SELECT * FROM `users` WHERE `verification_code` = '{$verificationCode}'";
    $result = $db->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        $sql = "UPDATE `users` SET `email_verified_at` = CURRENT_TIMESTAMP WHERE `id` = {$row['id']}";
        $db->query($sql);

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'failed', 'msg' => 'Invalid verification code.']);
} else {
    echo json_encode(['status' => 'failed', 'msg' => 'Invalid request.']);
}

?>
