<?php
require_once __DIR__ . '/auth.php';
require_login();

$user = current_user();
$studentId = (int) ($_GET['student_id'] ?? 0);

$studentStmt = $pdo->prepare('SELECT * FROM students WHERE id = :id AND user_id = :user_id');
$studentStmt->execute(['id' => $studentId, 'user_id' => $user['id']]);
$student = $studentStmt->fetch();

if (!$student) {
    set_flash('error', 'Student not found.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['info_title'] ?? '');
    $value = trim($_POST['info_value'] ?? '');

    if ($title === '' || $value === '') {
        set_flash('error', 'Title and value are required.');
        redirect('detail_add.php?student_id=' . $studentId);
    }

    // Table3: SHA1 hash for information value
    $sha1Hash = sha1($value);

    $insertStmt = $pdo->prepare(
        'INSERT INTO student_details (student_id, info_title, info_value, info_sha1)
         VALUES (:student_id, :info_title, :info_value, :info_sha1)'
    );

    $insertStmt->execute([
        'student_id' => $studentId,
        'info_title' => $title,
        'info_value' => $value,
        'info_sha1' => $sha1Hash,
    ]);

    set_flash('success', 'Information added with SHA1 hash.');
    redirect('index.php');
}

$pageTitle = 'Add Student Information';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>Add Information (Table3 - SHA1)</h2>
    <p>
        <strong>Student:</strong>
        <?= e($student['student_number'] . ' - ' . $student['first_name'] . ' ' . $student['last_name']); ?>
    </p>
    <form method="post">
        <div class="row">
            <div>
                <label>Information Title</label>
                <input type="text" name="info_title" placeholder="Example: Address" required>
            </div>
        </div>
        <div class="row">
            <div>
                <label>Information Value</label>
                <textarea name="info_value" placeholder="Example: Block 3 Lot 4" required></textarea>
            </div>
        </div>
        <button class="btn" type="submit">Save Information</button>
        <a class="btn secondary" href="index.php">Cancel</a>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php';
