# 🐳 Complete LAMP Stack with Docker

A complete LAMP (Linux, Apache, MySQL, PHP) development environment using Docker Compose with authentication system and phpMyAdmin.

## 📋 Features

- ✅ **Apache 2.4** with PHP 8.2
- ✅ **MySQL 8.0** with initialized database
- ✅ **phpMyAdmin** for database management
- ✅ **Login system** with secure authentication
- ✅ **Hybrid Docker networks** (web_network + db_network)
- ✅ **Environment variables** for secure credentials
- ✅ **Real-time development** with volumes

## 🚀 Quick Start

### 1. Clone the repository
```bash
git clone https://github.com/Alalzor/LAMP-Stack-Completo.git
cd LAMP-Stack-Completo
```

### 2. Configure environment variables
```bash
nano .env 
# Edit .env with your credentials
```

### 3. Start the environment
```bash
sudo docker compose up -d
```

### 4. Access points
- **Web Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **Custom domain**: http://projectdelta.local:8080 (requires /etc/hosts configuration)

## 🔐 Default Credentials

### Application users:
- **Admin**: `admin@projectdelta.local` / `admin123`
- **User**: `user@projectdelta.local` / `admin123`

### phpMyAdmin:
- **Username**: `root`
- **Password**: Defined in `.env` (default: `rootpassword`)

## 📁 Project Structure

```
LAMP-Stack-Completo/
├── www/                    # Web application
│   ├── dockerfile         # PHP + Apache image
│   ├── my-httpd.conf     # Apache configuration
│   └── htdocs/           # PHP source code
│       ├── index.php     # Login page
│       ├── login.php     # Login processing
│       ├── dashboard.php # Post-login dashboard
│       ├── logout.php    # Logout
│       └── config.php    # Database configuration
├── bdd/                   # Database
│   ├── dockerfile        # MySQL image
│   └── init.sql          # Database initialization
├── docker-compose.yml    # Service orchestration
├── .env                  # Environment variables template
└── .gitignore           # Git excluded files
```

## 🌐 Network Architecture

### Hybrid Network with Layered Security:
- **web_network**: Web services (Apache, phpMyAdmin)
- **db_network**: Database and services requiring DB access

```
┌─────────────┐    ┌─────────────┐
│     web     │────│ phpmyadmin  │
│  (Apache)   │    │             │
└─────┬───────┘    └─────┬───────┘
      │                  │
      └─────────┬────────┘
                │
         ┌──────▼──────┐
         │     db      │
         │  (MySQL)    │
         └─────────────┘
```

## 🛠️ Useful Commands

### Container management:
```bash
# Start in background
sudo docker compose up -d

# View logs
sudo docker compose logs -f

# Stop services
sudo docker compose down

# Rebuild images
sudo docker compose build --no-cache
```

### Container access:
```bash
# MySQL
sudo docker exec -it mysql_db mysql -u root -p

# Apache/PHP
sudo docker exec -it evaluable bash
```

## 🔧 Development

### Real-time editing:
Files in `www/htdocs/` automatically sync with the container thanks to Docker volumes. Just refresh the page to see changes.

### Adding new features:
1. Edit PHP files in `www/htdocs/`
2. Modify database from phpMyAdmin
3. Changes are immediate

## 📊 Database

### `users` table:
| Field       | Type          | Description         |
|-------------|---------------|---------------------|
| id          | INT (PK)      | Unique user ID      |
| email       | VARCHAR(255)  | Login email         |
| password    | VARCHAR(255)  | Password hash       |
| name        | VARCHAR(100)  | Full name           |
| created_at  | TIMESTAMP     | Creation date       |
| updated_at  | TIMESTAMP     | Last modification   |

## 🔒 Security

- ✅ Hashed passwords with `password_hash()`
- ✅ Environment variables for credentials
- ✅ Segmented Docker networks
- ✅ Form input validation
- ✅ Secure PHP session management

## 🚀 Upcoming Improvements

- [ ] Rate limiting to prevent brute force attacks
- [ ] Healthchecks for monitoring
- [ ] Docker resource limits
- [ ] Multi-stage builds for optimization
- [ ] SSL/TLS for HTTPS

## 📝 License

This project is under the MIT License. See `LICENSE` file for more details.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

---

⭐ **Like the project? Give it a star!** ⭐
