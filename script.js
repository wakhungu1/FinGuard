function renderChart(canvasId, data) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Income', 'Expenses', 'Savings'],
            datasets: [{
                data: [data.income, data.expenses, data.savings],
                backgroundColor: ['#8B4513', '#DC143C', '#228B22'] // Burnt Orange, Red, Green
            }]
        }
    });
}