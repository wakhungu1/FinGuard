<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FinGuard - Personal Finance Tracker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to FinGuard</h1>
        <p class="tagline">Track your finances easily and manage your money smarter!</p>
        
        <div class="auth-container">
            <div class="auth-section">
                <h2>Already have an account?</h2>
                <p>Log in to access your financial dashboard and track your income and expenses.</p>
                <a href="login.php" class="btn btn-primary">Login</a>
            </div>
            
            <div class="divider">or</div>
            
            <div class="auth-section">
                <h2>New to FinGuard?</h2>
                <p>Create an account to start tracking your finances today.</p>
                <a href="register.php" class="btn btn-secondary">Register</a>
            </div>
        </div>
        
        <div class="features">
            <h3>Why FinGuard?</h3>
            <ul>
                <li>📊 Track income and expenses</li>
                <li>📈 View financial reports (weekly, monthly, yearly)</li>
                <li>⚠️ Get alerted when you overspend</li>
                <li>🔐 Secure account with password protection</li>
            </ul>
        </div>
    </div>
</body>
</html>