# FinGuard - AI Coding Agent Instructions

## Project Overview
**FinGuard** is a personal finance tracking web application built with PHP, MySQL, and vanilla JavaScript. It allows users to register, log in, track income/expenses, and view financial reports across different time periods (weekly, monthly, yearly).

## Architecture & Data Flow

### Database Schema (implicit, from code analysis)
- **users**: `id`, `email`, `phone`, `password` (stores hashed passwords)
- **incomes**: `user_id`, `amount`, `date`
- **expenses**: `user_id`, `amount`, `category`, `date`

### Request Flow
1. **Authentication**: `index.php` → `login.php`/`register.php` → session creation
2. **Dashboard**: Authenticated users access `dashboard.php` (guarded by `$_SESSION['user_id']` check)
3. **Data Entry**: Income/expense forms POST to `dashboard.php`, insert to DB
4. **Reporting**: Report functions (`functions.php`) query aggregated data, render via Chart.js

### Key Integration Points
- **All database operations** use PDO prepared statements with `$pdo` global (defined in `config.php`)
- **Report data** flows from PHP functions → JSON encode → JavaScript → Chart.js rendering
- **Date calculations** use PHP `date()` and `strtotime()` for period selection

## Developer Workflows

### Setup
1. Configure database credentials in `config.php` (host, dbname, username, password)
2. Create MySQL database and tables using `CREATE DATABASE finance_tracker;.sql`
3. Serve via local web server (e.g., `php -S localhost:8000`)

### Adding New Features
- **New Report Period**: Add function following `getWeeklyReport()` pattern in `functions.php`
- **New Input Type**: Add form in `dashboard.php`, corresponding INSERT in POST handler, pass data to JS via `json_encode()`
- **Database Queries**: Use `$pdo->prepare()` with parameterized queries; never concatenate user input

### Testing Approach
- Test auth flow: register user → login → verify session → access dashboard
- Test data entry: add income/expense → verify DB insert → check report calculations
- Test edge cases: date boundaries, decimal amounts, missing optional fields

## Code Patterns & Conventions

### PHP Reporting Functions
All report functions follow this pattern:
```php
function getPeriodReport($user_id) {
    $start = date('Y-m-d', strtotime('...'));  // Calculate start date
    $end = date('Y-m-d', strtotime('...'));    // Calculate end date
    return [
        'income' => getUserData($user_id, 'incomes', $start, $end),
        'expenses' => getUserData($user_id, 'expenses', $start, $end),
        'savings' => calculateSavings($user_id, $start, $end)
    ];
}
```
- Always return associative array with `income`, `expenses`, `savings` keys
- Use `getUserData()` helper for aggregation; never hardcode table names

### Session Management
- Start session at top of every page that needs auth: `session_start();`
- Guard protected pages: `if (!isset($_SESSION['user_id'])) header('Location: index.php');`
- Logout simply destroys session and redirects to `index.php`

### Chart Rendering
JavaScript function `renderChart()` takes canvas ID and data object:
- Accepts object with `income`, `expenses`, `savings` numeric keys
- Creates pie chart with fixed colors (Burnt Orange, Red, Green)
- Called twice on dashboard: weekly and monthly data

## Known Issues & Quirks
- `functions.php` line 45 has stray "345" and line 51 has stray "h" - these are syntax artifacts that should be removed
- `login.php` line 1 has stray "w" character - syntax artifact
- `register.php` requires at least email OR phone, but doesn't validate that requirement client-side
- No input validation on expense amounts or income values (accepts any numeric input)
- Date calculations use current PHP server date; no timezone configuration

## Security Notes
- Passwords hashed with `PASSWORD_DEFAULT` (see `register.php`)
- All DB queries use prepared statements (SQL injection safe)
- Session-based authentication (not token-based)
- No CSRF tokens on forms - potential vulnerability
- No password strength validation during registration

## Common Modifications
1. **Add new report period**: Create function in `functions.php`, add form section in `dashboard.php`, update `script.js` chart calls
2. **Modify date ranges**: Edit `date()` and `strtotime()` calls in respective report functions
3. **Add expense categories**: Already flexible in code; just add dropdown in dashboard form, pass in POST
4. **Change color scheme**: Update color values in `styles.css` and `script.js`
