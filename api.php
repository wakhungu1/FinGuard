<?php
require 'config.php';
require 'functions.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    if ($action === 'add_income') {
        $income = floatval($_POST['income'] ?? 0);
        if ($income <= 0) {
            throw new Exception('Invalid income amount');
        }
        $stmt = $pdo->prepare("INSERT INTO incomes (user_id, amount, date) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $income, date('Y-m-d')]);
    } elseif ($action === 'add_expense') {
        $expense = floatval($_POST['expense'] ?? 0);
        $category = trim($_POST['category'] ?? '');
        if ($expense <= 0 || $category === '') {
            throw new Exception('Invalid expense or category');
        }
        $stmt = $pdo->prepare("INSERT INTO expenses (user_id, amount, category, date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $expense, $category, date('Y-m-d')]);
    }

    // Return updated reports
    $weekly = getWeeklyReport($user_id);
    $monthly = getMonthlyReport($user_id);
    $yearly = getYearlyReport($user_id);

    echo json_encode([
        'weekly' => $weekly,
        'monthly' => $monthly,
        'yearly' => $yearly
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
