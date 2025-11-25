# FinGuard - Personal Finance Tracker

A lightweight personal finance tracking web application built with **PHP**, **MySQL**, and **vanilla JavaScript**. Track your income and expenses, view financial reports across different time periods, and monitor overspending.

## Features

- 🔐 User authentication (register/login)
- 💰 Track income and expenses
- 📊 Financial reports (weekly, monthly, yearly)
- ⚠️ Overspending alerts
- 📈 Chart.js visualizations
- 🛡️ SQL injection protection with prepared statements

## Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Charts**: Chart.js
- **Authentication**: PHP Sessions, Password hashing with `PASSWORD_DEFAULT`

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, or PHP built-in server)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/wakhungu1/FinGuard.git
   cd FinGuard
   ```

2. **Create the database**
   ```bash
   mysql -u root -p < "CREATE DATABASE finance_tracker;.sql"
   ```

3. **Configure database credentials**
   - Open `config.php`
   - Update `$host`, `$dbname`, `$username`, `$password` with your database credentials
   ```php
   $host = 'localhost';
   $dbname = 'finance_tracker';
   $username = 'root';
   $password = 'your_password';
   ```

4. **Start the development server**
   ```bash
   php -S localhost:8000
   ```

5. **Access the application**
   - Open your browser and navigate to `http://localhost:8000`

## Usage

1. **Register**: Create a new account with email/phone and password
2. **Login**: Sign in with your credentials
3. **Add Income**: Track monthly income
4. **Add Expenses**: Record daily expenses with categories
5. **View Reports**: Check weekly, monthly, and yearly financial summaries
6. **Monitor Alerts**: Get notified when expenses exceed income

## Project Structure

```
FinGuard/
├── index.php              # Landing page
├── register.php           # User registration
├── login.php              # User login
├── dashboard.php          # Main app dashboard
├── logout.php             # Logout handler
├── functions.php          # Report generation functions
├── config.php             # Database configuration
├── styles.css             # Application styling
├── script.js              # Chart rendering
├── .github/
│   └── copilot-instructions.md  # AI agent guidelines
├── CREATE DATABASE finance_tracker;.sql  # Database schema
└── .gitignore             # Git ignore rules
```

## Database Schema

### users
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255),
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL
);
```

### incomes
```sql
CREATE TABLE incomes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### expenses
```sql
CREATE TABLE expenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    category VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## Key Code Patterns

### Report Functions
All report functions return an associative array with `income`, `expenses`, and `savings`:
```php
function getMonthlyReport($user_id) {
    $start = date('Y-m-01');
    $end = date('Y-m-t');
    return [
        'income' => getUserData($user_id, 'incomes', $start, $end),
        'expenses' => getUserData($user_id, 'expenses', $start, $end),
        'savings' => calculateSavings($user_id, $start, $end)
    ];
}
```

### Database Queries
All queries use prepared statements to prevent SQL injection:
```php
$stmt = $pdo->prepare("INSERT INTO incomes (user_id, amount, date) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $income, date('Y-m-d')]);
```

## Security Considerations

- ✅ Passwords hashed with `PASSWORD_DEFAULT` algorithm
- ✅ SQL injection prevention with prepared statements
- ✅ Session-based authentication
- ⚠️ Consider adding CSRF token protection for forms
- ⚠️ Add password strength validation in production

## Future Enhancements

- Budget planning and tracking
- Recurring transactions
- Export reports to CSV/PDF
- Multi-currency support
- Mobile app
- Email notifications
- Data backup and recovery

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open source and available under the MIT License.

## Author

**wakhungu1** - [GitHub Profile](https://github.com/wakhungu1)

## Support

For issues and questions, please open an issue on GitHub.
