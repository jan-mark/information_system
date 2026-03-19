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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentNumber = trim($_POST['student_number'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $yearLevel = (int) ($_POST['year_level'] ?? 0);

    if ($studentNumber === '' || $firstName === '' || $lastName === '' || $course === '' || $yearLevel <= 0) {
        set_flash('error', 'All student fields are required.');
        redirect('student_edit.php?id=' . $studentId);
    }

    $updateStmt = $pdo->prepare(
        'UPDATE students
         SET student_number = :student_number,
             first_name = :first_name,
             last_name = :last_name,
             course = :course,
             year_level = :year_level,
             updated_at = CURRENT_TIMESTAMP
         WHERE id = :id AND user_id = :user_id'
    );

    try {
        $updateStmt->execute([
            'student_number' => $studentNumber,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'course' => $course,
            'year_level' => $yearLevel,
            'id' => $studentId,
            'user_id' => $user['id'],
        ]);
        set_flash('success', 'Student updated successfully!');
        redirect('index.php');
    } catch (PDOException $e) {
        set_flash('error', 'Student number already exists.');
        redirect('student_edit.php?id=' . $studentId);
    }
}

$pageTitle = 'Edit Student';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>Edit Student</h2>
    <form method="post">
        <div class="row">
            <div>
                <label>Student Number</label>
                <input type="text" name="student_number" value="<?= e($student['student_number']); ?>" required>
            </div>
            <div>
                <label>First Name</label>
                <input type="text" name="first_name" value="<?= e($student['first_name']); ?>" required>
            </div>
            <div>
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?= e($student['last_name']); ?>" required>
            </div>
        </div>
        <div class="row">
            <div>
                <label>Course</label>
                <input type="text" name="course" value="<?= e($student['course']); ?>" required>
            </div>
            <div>
                <label>Year Level</label>
                <select name="year_level" required>
                    <?php for ($y = 1; $y <= 4; $y++): ?>
                        <option value="<?= $y; ?>" <?= ((int) $student['year_level'] === $y) ? 'selected' : ''; ?>>
                            <?= $y; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <button class="btn" type="submit">Update Student</button>
        <a class="btn secondary" href="index.php">Cancel</a>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php';
