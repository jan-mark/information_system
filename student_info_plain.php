<?php
require_once __DIR__ . '/auth.php';
require_login();

$user = current_user();

$studentsStmt = $pdo->prepare('SELECT * FROM students WHERE user_id = :user_id ORDER BY id DESC');
$studentsStmt->execute(['user_id' => $user['id']]);
$students = $studentsStmt->fetchAll();

$pageTitle = 'Student Information - Plain Text';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>Student Information - Plain Text View</h2>
    <p>Below is the plain text information (unencrypted) of all student records:</p>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Student No.</th>
                <th>Name</th>
                <th>Course / Section</th>
                <th>Year Level</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$students): ?>
                <tr>
                    <td colspan="6">No student records found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= (int) $student['id']; ?></td>
                        <td><?= e($student['student_number']); ?></td>
                        <td><?= e($student['first_name'] . ' ' . $student['last_name']); ?></td>
                        <td><?= e($student['course']); ?></td>
                        <td><?= (int) $student['year_level']; ?></td>
                        <td>
                            <a class="btn secondary" href="student_info_encrypted.php?id=<?= (int) $student['id']; ?>">View Encrypted</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="actions">
        <a class="btn" href="index.php">Back to Dashboard</a>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php';
