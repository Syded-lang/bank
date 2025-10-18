# Laravel Banking Application

A complete banking management system built with Laravel.

## Features
- User management
- Account management
- Transaction handling
- Multiple payment gateway integrations
- Admin dashboard
- Branch staff management
- Multi-language support

## Requirements
- PHP 8.1+
- MySQL 5.7+
- Composer
- Laravel 10+

## Installation

1. Clone the repository
```bash
git clone git@github.com:Syded-lang/bank.git
cd bank
```

2. Navigate to core directory
```bash
cd core
```

3. Install dependencies
```bash
composer install
```

4. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

5. Update `.env` file with your database credentials
```
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations
```bash
php artisan migrate
```

7. Serve the application
```bash
php artisan serve
```

## Deployment

For shared hosting (Hostinger, etc.):
1. Upload all files to `public_html/`
2. Ensure `.htaccess` is properly configured
3. Set folder permissions:
   - `core/storage/` → 775
   - `core/bootstrap/cache/` → 775
4. Update `core/.env` with production settings
5. Set `APP_DEBUG=false` in production

## Project Structure
```
.
├── .htaccess           # Root routing configuration
├── index.php           # Bootstrap file
├── core/               # Laravel application
│   ├── app/           # Application logic
│   ├── config/        # Configuration files
│   ├── database/      # Migrations
│   ├── public/        # Web assets
│   ├── resources/     # Views and languages
│   ├── routes/        # Route definitions
│   ├── storage/       # Logs and cache
│   └── vendor/        # Dependencies
└── install/           # Installation wizard
```

## Payment Gateways Supported
- Stripe
- Razorpay
- PayPal
- Mollie
- Authorize.Net
- BTCPay Server
- CoinGate

## License
All rights reserved.

## Support
For support, please contact the development team.
