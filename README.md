<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
=======
# HomyGo - Property Rental Platform

A comprehensive property rental platform built with Laravel 12, featuring AI-powered recommendations, enterprise security, and social authentication.

## 🏠 Features

- **Property Management**: List, search, and book properties
- **Social Authentication**: Facebook & Google login
- **AI Recommendations**: Personalized property suggestions
- **Enterprise Security**: Advanced threat detection and monitoring
- **Mobile Responsive**: Optimized for all devices
- **Multi-role System**: Admin, Landlord, and Renter roles

## 🚀 Quick Deploy to Render

[![Deploy to Render](https://render.com/images/deploy-to-render-button.svg)](https://render.com/deploy?repo=https://github.com/Homygo25/HomyGO-2025)

## 📋 Environment Variables

Set these in your deployment platform:

```bash
APP_NAME=HomyGo
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.onrender.com
APP_KEY=base64:TtnljvZvTe5cQwUqZzeSjD4VWNi/JUSucZfGd2xEIho=

# Database (PostgreSQL for Render)
DB_CONNECTION=pgsql

# Cache & Sessions
CACHE_DRIVER=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Social Auth (Optional)
FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
```

## 🛠️ Local Development

1. Clone the repository:
```bash
git clone https://github.com/Homygo25/HomyGO-2025.git
cd HomyGO-2025
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations:
```bash
php artisan migrate --seed
```

5. Build assets and start server:
```bash
npm run build
php artisan serve
```

## 🔧 Production Deployment

### Render.com Deployment

1. Connect your GitHub repository to Render
2. Set environment variables from the list above
3. Use these build/start commands:

**Build Command:**
```bash
composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan migrate --force && php artisan config:cache
```

**Start Command:**
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Manual Deployment

1. Set up production environment variables
2. Run deployment script:
```bash
chmod +x render-build.sh
./render-build.sh
```

## 📊 System Requirements

- PHP 8.2+
- Node.js 18+
- PostgreSQL (production) / SQLite (development)
- Composer
- NPM

## 🔐 Security Features

- Enterprise-grade security middleware
- Rate limiting and threat detection
- Role-based access control
- Social authentication
- Data privacy compliance

## 📱 Mobile Support

- Fully responsive design
- Touch-optimized interface
- Progressive Web App features
- Mobile-first approach

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 📞 Support

For support, email support@homygo.com or create an issue on GitHub.

---

Built with ❤️ for the Filipino property rental market.
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

<<<<<<< HEAD
Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.
=======
Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
