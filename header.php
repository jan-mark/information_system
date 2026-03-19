<?php
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) : 'Student Information System'; ?></title>
    <style>
        :root {
            --bg: #f4f7fb;
            --card: #ffffff;
            --text: #1a2a3a;
            --muted: #4b5f73;
            --primary: #0f766e;
            --primary-dark: #0b5f59;
            --danger: #b42318;
            --border: #d8e1eb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
            color: var(--text);
            background: linear-gradient(160deg, #e8f3ff 0%, #f6fbf4 100%);
        }
        .container {
            max-width: 1060px;
            margin: 32px auto;
            padding: 0 16px;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            gap: 12px;
            flex-wrap: wrap;
        }
        .brand {
            margin: 0;
            font-size: 1.35rem;
        }
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 6px 20px rgba(11, 53, 79, 0.06);
        }
        .btn {
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            background: var(--primary);
            color: #fff;
        }
        .btn:hover { background: var(--primary-dark); }
        .btn.secondary {
            background: #556b82;
        }
        .btn.danger {
            background: var(--danger);
        }
        form .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin-bottom: 12px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.92rem;
            color: var(--muted);
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #b5c3d1;
            border-radius: 8px;
            font-size: 0.95rem;
        }
        textarea { min-height: 90px; resize: vertical; }
        .table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        th, td {
            border-bottom: 1px solid var(--border);
            padding: 10px;
            text-align: left;
            vertical-align: top;
            font-size: 0.92rem;
        }
        th { background: #f0f6fb; color: #27435d; }
        .hash {
            font-family: Consolas, monospace;
            font-size: 0.82rem;
            word-break: break-all;
            color: #304960;
        }
        .alert {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            font-size: 0.92rem;
        }
        .alert.error { background: #fdecec; color: #8b1e1e; border: 1px solid #f7cccc; }
        .alert.success { background: #eafbf3; color: #14532d; border: 1px solid #c7f0d7; }
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <h1 class="brand">Student Information System</h1>
        <div class="actions">
            <?php if ($user): ?>
                <span>Logged in as <strong><?= e($user['username']); ?></strong></span>
                <a class="btn secondary" href="index.php">Dashboard</a>
                <a class="btn danger" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="btn" href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($error = get_flash('error')): ?>
        <div class="alert error"><?= e($error); ?></div>
    <?php endif; ?>
    <?php if ($success = get_flash('success')): ?>
        <div class="alert success"><?= e($success); ?></div>
    <?php endif; ?>
