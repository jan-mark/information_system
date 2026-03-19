<?php
require_once __DIR__ . '/auth.php';
require_login();

$user = current_user();
$studentId = (int) ($_POST['student_id'] ?? 0);
$fieldType = trim($_POST['field_type'] ?? '');
$plainValue = trim($_POST['plain_value'] ?? '');

$stmt = $pdo->prepare('SELECT id, user_id FROM students WHERE id = :id');
$stmt->execute(['id' => $studentId]);
$student = $stmt->fetch();

if (!$student || $student['user_id'] != $user['id']) {
    set_flash('error', 'Student not found.');
    redirect('index.php');
}

if ($fieldType === '' || $plainValue === '') {
    set_flash('error', 'All fields are required.');
    redirect('student_info_encrypted.php?id=' . $studentId);
}

// Generate hashes based on field type (only specific hash for each field)
$md5Hash = null;
$bcryptHash = null;
$sha256Hash = null;

if ($fieldType === 'Email') {
    $bcryptHash = password_hash($plainValue, PASSWORD_BCRYPT);
} elseif ($fieldType === 'Nickname') {
    $md5Hash = md5($plainValue);
} elseif ($fieldType === 'Remarks') {
    $sha256Hash = hash('sha256', $plainValue);
}

$insertStmt = $pdo->prepare(
    'INSERT INTO student_info (student_id, field_type, plain_value, md5_hash, bcrypt_hash, sha256_hash)
     VALUES (:student_id, :field_type, :plain_value, :md5_hash, :bcrypt_hash, :sha256_hash)'
);

$insertStmt->execute([
    'student_id' => $studentId,
    'field_type' => $fieldType,
    'plain_value' => $plainValue,
    'md5_hash' => $md5Hash,
    'bcrypt_hash' => $bcryptHash,
    'sha256_hash' => $sha256Hash,
]);

set_flash('success', 'Information added successfully!');
redirect('student_info_encrypted.php?id=' . $studentId);
