<?php
require_once __DIR__ . '/auth.php';
require_login();

$user = current_user();
$studentId = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id AND user_id = :user_id');
$stmt->execute(['id' => $studentId, 'user_id' => $user['id']]);
$student = $stmt->fetch();

if (!$student) {
    set_flash('error', 'Student not found.');
    redirect('index.php');
}

$infoStmt = $pdo->prepare(
    'SELECT * FROM student_info WHERE student_id = :student_id ORDER BY id DESC'
);
$infoStmt->execute(['student_id' => $studentId]);
$infos = $infoStmt->fetchAll();

$pageTitle = 'Student Information - Encrypted Data';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>Encrypted Information - <?= e($student['first_name'] . ' ' . $student['last_name']); ?></h2>
    <p><strong>Student Number:</strong> <?= e($student['student_number']); ?></p>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Field Type</th>
                <th>Email Encryption (Bcrypt)</th>
                <th>Nickname Encryption (MD5)</th>
                <th>Remarks Encryption (SHA256)</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $emailInfo = null;
            $nicknameInfo = null;
            $remarksInfo = null;
            
            foreach ($infos as $info) {
                if ($info['field_type'] === 'Email') {
                    $emailInfo = $info;
                } elseif ($info['field_type'] === 'Nickname') {
                    $nicknameInfo = $info;
                } elseif ($info['field_type'] === 'Remarks') {
                    $remarksInfo = $info;
                }
            }
            ?>
            <tr>
                <td><strong>Data</strong></td>
                <td class="hash"><?= $emailInfo ? e($emailInfo['bcrypt_hash']) : 'N/A'; ?></td>
                <td class="hash"><?= $nicknameInfo ? e($nicknameInfo['md5_hash']) : 'N/A'; ?></td>
                <td class="hash"><?= $remarksInfo ? e($remarksInfo['sha256_hash']) : 'N/A'; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php';
