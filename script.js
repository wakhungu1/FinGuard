const charts = {};

function renderChart(canvasId, data) {
    const el = document.getElementById(canvasId);
    if (!el) return null;
    const ctx = el.getContext('2d');
    const dataset = [Number(data.income) || 0, Number(data.expenses) || 0, Number(data.savings) || 0];

    const config = {
        type: 'pie',
        data: {
            labels: ['Income', 'Expenses', 'Savings'],
            datasets: [{
                data: dataset,
                backgroundColor: ['#8B4513', '#DC143C', '#228B22'] // Burnt Orange, Red, Green
            }]
        },
        options: { responsive: true }
    };

    if (charts[canvasId]) {
        charts[canvasId].data.datasets[0].data = dataset;
        charts[canvasId].update();
        return charts[canvasId];
    }

    charts[canvasId] = new Chart(ctx, config);
    return charts[canvasId];
}

// Attach live handlers to forms to POST to api.php and update charts without reload
document.addEventListener('DOMContentLoaded', function () {
    const incomeForm = document.getElementById('incomeForm');
    const expenseForm = document.getElementById('expenseForm');

    async function submitForm(action, form) {
        const formData = new FormData(form);
        formData.append('action', action);
        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const json = await res.json();
            if (!res.ok) {
                alert(json.error || 'Server error');
                return;
            }
            // update charts
            if (json.weekly) renderChart('weeklyChart', json.weekly);
            if (json.monthly) renderChart('monthlyChart', json.monthly);
            if (json.daily) renderChart('dailyChart', json.daily);
            if (json.yearly) {
                const yearlyEl = document.getElementById('yearlySummary');
                if (yearlyEl) yearlyEl.textContent = `Income: $${json.yearly.income}, Expenses: $${json.yearly.expenses}, Savings: $${json.yearly.savings}`;
            }
            form.reset();
        } catch (err) {
            console.error(err);
            alert('Network error');
        }
    }

    if (incomeForm) {
        incomeForm.addEventListener('submit', function (e) {
            e.preventDefault();
            submitForm('add_income', incomeForm);
        });
    }

    if (expenseForm) {
        expenseForm.addEventListener('submit', function (e) {
            e.preventDefault();
            submitForm('add_expense', expenseForm);
        });
    }
});