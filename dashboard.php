<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: index.php');
require 'config.php';
require 'functions.php';
$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT email, phone FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$user_name = $user['email'] ?? $user['phone'] ?? 'User';
$current_date = date('F j, Y');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['income'])) {
        $income = floatval($_POST['income']);
        if ($income > 0) {
            $stmt = $pdo->prepare("INSERT INTO incomes (user_id, amount, date) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $income, date('Y-m-d')]);
        }
    } elseif (isset($_POST['expense'])) {
        $expense = floatval($_POST['expense']);
        $category = trim($_POST['category'] ?? '');
        if ($expense > 0 && $category) {
            $stmt = $pdo->prepare("INSERT INTO expenses (user_id, amount, category, date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $expense, $category, date('Y-m-d')]);
        }
    }
}

$daily = getDailyReport($user_id);
$weekly = getWeeklyReport($user_id);
$monthly = getMonthlyReport($user_id);
$yearly = getYearlyReport($user_id);
$overspending = isOverspending($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Welcome to FinGuard</h2>
        <div class="user-info">
            <p><strong>User:</strong> <?php echo htmlspecialchars($user_name); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($current_date); ?></p>
        </div>
        <?php if ($overspending): ?>
            <p class="alert">You're overspending! Try to save more.</p>
        <?php endif; ?>

        <h3>Add Monthly Income</h3>
        <form id="incomeForm" method="POST">
            <input type="number" name="income" step="0.01" placeholder="Amount" required>
            <button type="submit">Add Income</button>
        </form>

        <h3>Add Daily Expense</h3>
        <form id="expenseForm" method="POST">
            <input type="number" name="expense" step="0.01" placeholder="Amount" required>
            <input type="text" name="category" placeholder="Category" required>
            <button type="submit">Add Expense</button>
        </form>

        <h3>Daily Report</h3>
        <canvas id="dailyChart"></canvas>

        <h3>Weekly Report</h3>
        <canvas id="weeklyChart"></canvas>

        <h3>Monthly Report</h3>
        <canvas id="monthlyChart"></canvas>

        <h3>Yearly Report</h3>
        <p id="yearlySummary">Income: $<?php echo $yearly['income']; ?>, Expenses: $<?php echo $yearly['expenses']; ?>, Savings: $<?php echo $yearly['savings']; ?></p>

        <a href="logout.php">Logout</a>
    </div>
    <script src="script.js"></script>
    <script>
        // Pass PHP data to JS
        const dailyData = <?php echo json_encode($daily); ?>;
        const weeklyData = <?php echo json_encode($weekly); ?>;
        const monthlyData = <?php echo json_encode($monthly); ?>;
        renderChart('dailyChart', dailyData);
        renderChart('weeklyChart', weeklyData);
        renderChart('monthlyChart', monthlyData);
        const yearlyData = <?php echo json_encode($yearly); ?>;
        document.addEventListener('DOMContentLoaded', function() {
            const yearlyEl = document.getElementById('yearlySummary');
            if (yearlyEl) {
                yearlyEl.textContent = `Income: $${yearlyData.income}, Expenses: $${yearlyData.expenses}, Savings: $${yearlyData.savings}`;
            }
        });
    </script>
</body>
</html>