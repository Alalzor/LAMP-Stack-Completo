# 🐳 Complete Secure LAMP Stack with Docker & SSL

A production-ready LAMP (Linux, Apache, MySQL, PHP) development environment using Docker Compose with comprehensive security features, SSL/HTTPS encryption, and authentication system.

## 📋 Features

- ✅ **Apache 2.4** with PHP 8.2
- ✅ **MySQL 8.0** with initialized database
- ✅ **phpMyAdmin** for database management
- ✅ **Login system** with secure authentication
- ✅ **SSL/HTTPS encryption** with self-signed certificates
- ✅ **Automatic HTTP → HTTPS redirect**
- ✅ **Advanced security headers and configurations**
- ✅ **Rate limiting and threat detection**
- ✅ **Hybrid Docker networks** (web_network + db_network)
- ✅ **Environment variables** for secure credentials
- ✅ **Real-time development** with volumes
- ✅ **Container healthchecks** and resource optimization

## 🚀 Quick Start

### 1. Clone the repository
```bash
git clone https://github.com/Alalzor/LAMP-Stack-Completo.git
cd LAMP-Stack-Completo
```

### 2. Configure environment variables
```bash
cp .env.example .env
# Edit .env with your credentials
```

### 3. Generate SSL certificates
```bash
# Create SSL directory
mkdir -p ssl

# Generate self-signed certificates
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout ssl/projectdelta.key \
    -out ssl/projectdelta.crt \
    -subj "/C=ES/ST=Madrid/L=Madrid/O=Project Delta/OU=Development/CN=localhost/emailAddress=admin@projectdelta.local"

# Set proper permissions
chmod 600 ssl/projectdelta.key
chmod 644 ssl/projectdelta.crt
```

### 4. Add host resolution (optional)
```bash
# Add to /etc/hosts for custom domain
echo "127.0.0.1 projectdelta" | sudo tee -a /etc/hosts
```

### 5. Start the environment
```bash
sudo docker compose build --no-cache
sudo docker compose up -d
```

### 6. Access points
- **Web Application (HTTP)**: http://localhost:8080 (auto-redirects to HTTPS)
- **Web Application (HTTPS)**: https://projectdelta:8443
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3306

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
│   ├── dockerfile         # PHP + Apache + SSL configuration
│   ├── my-httpd.conf      # HTTP → HTTPS redirect config
│   ├── ssl-site.conf      # HTTPS VirtualHost with security
│   ├── security.ini       # PHP security configuration
│   └── htdocs/            # PHP source code
│       ├── index.php      # Login page
│       ├── login.php      # Login processing
│       ├── dashboard.php  # Post-login dashboard
│       ├── logout.php     # Logout
│       ├── config.php     # Database configuration
│       └── security.php   # Security monitoring functions
├── bdd/                   # Database
│   ├── dockerfile         # MySQL image
│   └── init.sql           # Database initialization
├── ssl/                   # SSL certificates
│   ├── projectdelta.crt   # SSL certificate
│   └── projectdelta.key   # SSL private key
├── docker-compose.yml     # Service orchestration
├── .env.example           # Environment variables template
└── .gitignore            # Git excluded files
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

### Current Authentication:
The current implementation uses **hardcoded test users** for simplicity:
- `admin@projectdelta.local` / `admin123`
- `user@projectdelta.local` / `admin123`

### Planned Database Integration:
Future versions will include a proper `users` table:
| Field       | Type          | Description         |
|-------------|---------------|---------------------|
| id          | INT (PK)      | Unique user ID      |
| email       | VARCHAR(255)  | Login email         |
| password    | VARCHAR(255)  | Password hash       |
| name        | VARCHAR(100)  | Full name           |
| created_at  | TIMESTAMP     | Creation date       |
| updated_at  | TIMESTAMP     | Last modification   |

## 🔒 Comprehensive Security Features

### 🛡️ SSL/HTTPS Security:
- ✅ **SSL/TLS Encryption** with self-signed certificates
- ✅ **Automatic HTTP → HTTPS redirect** (301 permanent)
- ✅ **HSTS (HTTP Strict Transport Security)** headers
- ✅ **Strong SSL cipher suites** and protocols (TLS 1.2+)
- ✅ **SSL certificate validation** and proper key management

### 🔐 Authentication & Session Security:
- ✅ **Simple authentication system** with hardcoded test users
- ✅ **Secure session configuration** with HTTPOnly cookies
- ✅ **Session regeneration** on login to prevent fixation
- ✅ **Secure logout** with session destruction

### 🚫 Rate Limiting & Attack Prevention:
- ✅ **Login rate limiting** (5 attempts per 15 minutes)
- ✅ **Basic input validation and sanitization** on login form
- ✅ **HTTP method restrictions** (only GET, POST, HEAD allowed)
- ✅ **Basic threat detection** for malicious patterns

### 🌐 Infrastructure Security:
- ✅ **Environment variables** for sensitive credentials
- ✅ **Docker network segmentation** (hybrid architecture)
- ✅ **Container resource limits** and healthchecks
- ✅ **Apache security headers** (X-Frame-Options, X-XSS-Protection, etc.)
- ✅ **File access restrictions** (.env, .git, sensitive files blocked)

### 🔍 Advanced Security Monitoring:
- ✅ **Basic threat detection** for common attack patterns
- ✅ **Security event logging** with detailed audit trails
- ✅ **Suspicious IP detection** and monitoring
- ✅ **PHP security hardening** (disabled dangerous functions)
- ✅ **Directory listing disabled** and index restrictions

### 📊 Security Headers Implemented:
```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=(), camera=()
Strict-Transport-Security: max-age=31536000; includeSubDomains
```

## 🚀 Recent Security Improvements ✅

### ✅ **PRODUCTION-READY SECURITY FEATURES COMPLETED:**

#### 🔐 SSL/HTTPS Implementation:
- ✅ **Complete SSL/TLS encryption** with certificate generation
- ✅ **Automatic HTTP → HTTPS redirect** (all traffic secured)
- ✅ **HSTS enforcement** for browser security
- ✅ **Strong cipher suites** and TLS 1.2+ enforcement

#### 🛡️ Basic Security Hardening:
- ✅ **Comprehensive security headers** implementation
- ✅ **Rate limiting system** for brute force protection
- ✅ **Basic threat detection** and logging
- ✅ **Container security optimization** with healthchecks
- ✅ **PHP security hardening** configuration
- ✅ **File system security** with access restrictions

#### 🔍 Security Monitoring & Logging:
- ✅ **Security event logging** with detailed audit trails
- ✅ **Basic threat detection algorithms** for common attacks
- ✅ **IP-based monitoring** and suspicious activity detection

#### 🏗️ Infrastructure Security:
- ✅ **Docker container hardening** with resource limits
- ✅ **Network segmentation** with hybrid architecture
- ✅ **Environment variable security** for credential management
- ✅ **Apache configuration optimization** for security

### 🎯 **SECURITY CHECKLIST - CURRENT STATUS:**
- [x] SSL/TLS encryption
- [x] HTTPS enforcement with automatic redirect
- [x] Security headers implementation
- [x] Rate limiting for login attempts
- [x] Basic input validation on login form
- [x] HTTP method restrictions
- [x] Session security
- [x] Container security and resource limits
- [x] Network segmentation
- [x] Security monitoring and logging
- [x] Basic threat detection algorithms
- [ ] CSRF protection (planned)
- [ ] Password hashing with bcrypt (planned)
- [ ] Enhanced XSS protection (planned)

### 🔧 Advanced Configuration

### 🚧 **PLANNED SECURITY ENHANCEMENTS:**

#### Next Security Features to Implement:
- [ ] **CSRF Protection** with token validation for forms
- [ ] **Password Hashing** with bcrypt (currently using plain text)
- [ ] **Enhanced Input Sanitization** for login form
- [ ] **XSS Protection** with proper output escaping

### SSL Certificate Management:
```bash
# Generate production certificates with custom parameters
openssl req -x509 -nodes -days 365 -newkey rsa:4096 \
    -keyout ssl/projectdelta.key \
    -out ssl/projectdelta.crt \
    -config <(
    echo '[dn]'
    echo 'CN=projectdelta.local'
    echo '[req]'
    echo 'distinguished_name = dn'
    echo '[SAN]'
    echo 'subjectAltName=DNS:projectdelta.local,DNS:localhost,IP:127.0.0.1'
    echo '[v3_req]'
    echo 'subjectAltName = @SAN'
    )
```

### Security Testing:
```bash
# Test SSL configuration
openssl s_client -connect projectdelta:8443 -servername projectdelta

# Test security headers
curl -I -k https://projectdelta:8443

# Test redirect functionality
curl -I http://projectdelta:8080
```

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
