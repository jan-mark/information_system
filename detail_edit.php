<?php
require_once __DIR__ . '/auth.php';
require_login();

$user = current_user();
$detailId = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    'SELECT sd.*, s.user_id, s.student_number, s.first_name, s.last_name
     FROM student_details sd
     INNER JOIN students s ON s.id = sd.student_id
     WHERE sd.id = :id AND s.user_id = :user_id'
);
$stmt->execute(['id' => $detailId, 'user_id' => $user['id']]);
$detail = $stmt->fetch();

if (!$detail) {
    set_flash('error', 'Information entry not found.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['info_title'] ?? '');
    $value = trim($_POST['info_value'] ?? '');

    if ($title === '' || $value === '') {
        set_flash('error', 'Title and value are required.');
        redirect('detail_edit.php?id=' . $detailId);
    }

    $sha1Hash = sha1($value);

    $updateStmt = $pdo->prepare(
        'UPDATE student_details
         SET info_title = :info_title,
             info_value = :info_value,
             info_sha1 = :info_sha1,
             updated_at = CURRENT_TIMESTAMP
         WHERE id = :id'
    );

    $updateStmt->execute([
        'info_title' => $title,
        'info_value' => $value,
        'info_sha1' => $sha1Hash,
        'id' => $detailId,
    ]);

    set_flash('success', 'Information updated and SHA1 refreshed.');
    redirect('index.php');
}

$pageTitle = 'Edit Student Information';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>Edit Information (Table3 - SHA1)</h2>
    <p>
        <strong>Student:</strong>
        <?= e($detail['student_number'] . ' - ' . $detail['first_name'] . ' ' . $detail['last_name']); ?>
    </p>
    <form method="post">
        <div class="row">
            <div>
                <label>Information Title</label>
                <input type="text" name="info_title" value="<?= e($detail['info_title']); ?>" required>
            </div>
        </div>
        <div class="row">
            <div>
                <label>Information Value</label>
                <textarea name="info_value" required><?= e($detail['info_value']); ?></textarea>
            </div>
        </div>
        <button class="btn" type="submit">Update Information</button>
        <a class="btn secondary" href="index.php">Cancel</a>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php';
