<?php
require 'config.php';

function getUserData($user_id, $table, $start_date, $end_date) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(amount) as total FROM $table WHERE user_id = ? AND date BETWEEN ? AND ?");
    $stmt->execute([$user_id, $start_date, $end_date]);
    return $stmt->fetch()['total'] ?? 0;
}

function calculateSavings($user_id, $start_date, $end_date) {
    $income = getUserData($user_id, 'incomes', $start_date, $end_date);
    $expenses = getUserData($user_id, 'expenses', $start_date, $end_date);
    return $income - $expenses;
}

function getWeeklyReport($user_id) {
    $start = date('Y-m-d', strtotime('monday this week'));
    $end = date('Y-m-d', strtotime('sunday this week'));
    return [
        'income' => getUserData($user_id, 'incomes', $start, $end),
        'expenses' => getUserData($user_id, 'expenses', $start, $end),
        'savings' => calculateSavings($user_id, $start, $end)
    ];
}

function getMonthlyReport($user_id) {
    $start = date('Y-m-01');
    $end = date('Y-m-t');
    return [
        'income' => getUserData($user_id, 'incomes', $start, $end),
        'expenses' => getUserData($user_id, 'expenses', $start, $end),
        'savings' => calculateSavings($user_id, $start, $end)
    ];
}

function getYearlyReport($user_id) {
    $start = date('Y-01-01');
    $end = date('Y-12-31');
    return [
        'income' => getUserData($user_id, 'incomes', $start, $end),
        'expenses' => getUserData($user_id, 'expenses', $start, $end),
        'savings' => calculateSavings($user_id, $start, $end)
    ];
}

function getDailyReport($user_id) {
    $start = date('Y-m-d');
    $end = date('Y-m-d');
    return [
        'income' => getUserData($user_id, 'incomes', $start, $end),
        'expenses' => getUserData($user_id, 'expenses', $start, $end),
        'savings' => calculateSavings($user_id, $start, $end)
    ];
}

function isOverspending($user_id) {
    $monthly = getMonthlyReport($user_id);
    return $monthly['expenses'] > $monthly['income'];
}
?>