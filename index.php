<?php
require_once __DIR__ . '/auth.php';
require_login();

$user = current_user();

$studentsStmt = $pdo->prepare('SELECT * FROM students WHERE user_id = :user_id ORDER BY id DESC');
$studentsStmt->execute(['user_id' => $user['id']]);
$students = $studentsStmt->fetchAll();

if (!isset($pageTitle)) {
    $pageTitle = 'Dashboard';
}

$pageTitle = 'Dashboard';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>Quick Actions</h2>
    <div class="actions">
        <a class="btn" href="student_add.php">Add Student</a>
        <a class="btn secondary" href="student_info_plain.php">View Plain Text Information</a>
    </div>
</div>

<div class="card">
    <h2>All Students </h2>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Student No.</th>
                <th>Name</th>
                <th>Course</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$students): ?>
                <tr>
                    <td colspan="6">No student records yet.</td>
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
                            <a class="btn secondary" href="student_edit.php?id=<?= (int) $student['id']; ?>">Edit</a>
                            <a class="btn" href="student_info_encrypted.php?id=<?= (int) $student['id']; ?>">View Encrypted</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php';
