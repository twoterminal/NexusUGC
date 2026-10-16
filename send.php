<?php
header('Content-Type: application/json');
$to   = 'coordinator@nexusugc.com';      // your inbox
$from = 'no-reply@nexusugc.com';         // must be an address on YOUR domain

$d = json_decode(file_get_contents('php://input'), true);
$email = filter_var($d['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$email || empty($d['message'])) {
  http_response_code(400);
  echo json_encode(['success' => false]); exit;
}

$subject = str_replace(["\r","\n"], ' ', substr($d['subject'] ?? 'Campaign brief', 0, 150));
$headers = "From: $from\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";
$ok = mail($to, $subject, $d['message'], $headers);

http_response_code($ok ? 200 : 500);
echo json_encode(['success' => $ok]);
