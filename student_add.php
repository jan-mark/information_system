<?php
require_once __DIR__ . '/auth.php';
require_login();

$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentNumber = trim($_POST['student_number'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $yearLevel = (int) ($_POST['year_level'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $nickname = trim($_POST['nickname'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');

    if ($studentNumber === '' || $firstName === '' || $lastName === '' || $course === '' || $yearLevel <= 0) {
        set_flash('error', 'All student fields are required.');
        redirect('student_add.php');
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert student
        $stmt = $pdo->prepare(
            'INSERT INTO students (user_id, student_number, first_name, last_name, course, year_level)
             VALUES (:user_id, :student_number, :first_name, :last_name, :course, :year_level)'
        );

        $stmt->execute([
            'user_id' => $user['id'],
            'student_number' => $studentNumber,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'course' => $course,
            'year_level' => $yearLevel,
        ]);

        $studentId = $pdo->lastInsertId();

        // Insert email if provided (Bcrypt only)
        if ($email !== '') {
            $bcryptHash = password_hash($email, PASSWORD_BCRYPT);
            $infoStmt = $pdo->prepare(
                'INSERT INTO student_info (student_id, field_type, plain_value, md5_hash, bcrypt_hash, sha256_hash)
                 VALUES (:student_id, :field_type, :plain_value, :md5_hash, :bcrypt_hash, :sha256_hash)'
            );
            $infoStmt->execute([
                'student_id' => $studentId,
                'field_type' => 'Email',
                'plain_value' => $email,
                'md5_hash' => null,
                'bcrypt_hash' => $bcryptHash,
                'sha256_hash' => null,
            ]);
        }

        // Insert nickname if provided (MD5 only)
        if ($nickname !== '') {
            $md5Hash = md5($nickname);
            $infoStmt = $pdo->prepare(
                'INSERT INTO student_info (student_id, field_type, plain_value, md5_hash, bcrypt_hash, sha256_hash)
                 VALUES (:student_id, :field_type, :plain_value, :md5_hash, :bcrypt_hash, :sha256_hash)'
            );
            $infoStmt->execute([
                'student_id' => $studentId,
                'field_type' => 'Nickname',
                'plain_value' => $nickname,
                'md5_hash' => $md5Hash,
                'bcrypt_hash' => null,
                'sha256_hash' => null,
            ]);
        }

        // Insert remarks if provided (SHA256 only)
        if ($remarks !== '') {
            $sha256Hash = hash('sha256', $remarks);
            $infoStmt = $pdo->prepare(
                'INSERT INTO student_info (student_id, field_type, plain_value, md5_hash, bcrypt_hash, sha256_hash)
                 VALUES (:student_id, :field_type, :plain_value, :md5_hash, :bcrypt_hash, :sha256_hash)'
            );
            $infoStmt->execute([
                'student_id' => $studentId,
                'field_type' => 'Remarks',
                'plain_value' => $remarks,
                'md5_hash' => null,
                'bcrypt_hash' => null,
                'sha256_hash' => $sha256Hash,
            ]);
        }

        // Commit transaction
        $pdo->commit();

        set_flash('success', 'Student and information added successfully!');
        redirect('index.php');
    } catch (PDOException $e) {
        $pdo->rollBack();
        if (strpos($e->getMessage(), 'student_number') !== false) {
            set_flash('error', 'Student number already exists.');
        } else {
            set_flash('error', 'An error occurred while adding the student.');
        }
        redirect('student_add.php');
    }
}

$pageTitle = 'Add Student';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>Add Student Record</h2>
    <form method="post">
        <div class="row">
            <div>
                <label>Student Number</label>
                <input type="text" name="student_number" required>
            </div>
            <div>
                <label>First Name</label>
                <input type="text" name="first_name" required>
            </div>
            <div>
                <label>Last Name</label>
                <input type="text" name="last_name" required>
            </div>
        </div>
        <div class="row">
            <div>
                <label>Course</label>
                <input type="text" name="course" required>
            </div>
            <div>
                <label>Year Level</label>
                <select name="year_level" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div>
                <label>Email <span style="color: #999; font-size: 0.9em;">(Optional)</span></label>
                <input type="email" name="email" placeholder="e.g., student@email.com">
            </div>
            <div>
                <label>Nickname <span style="color: #999; font-size: 0.9em;">(Optional)</span></label>
                <input type="text" name="nickname" placeholder="e.g., Johnny">
            </div>
            <div>
                <label>Remarks <span style="color: #999; font-size: 0.9em;">(Optional)</span></label>
                <input type="text" name="remarks" placeholder="e.g., Any additional notes">
            </div>
        </div>
        <button class="btn" type="submit">Save Student</button>
        <a class="btn secondary" href="index.php">Cancel</a>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php';
