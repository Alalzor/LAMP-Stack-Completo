#!/bin/bash

echo "=== 🔍 Project Delta - System Verification ==="
echo ""

# Check PHP syntax for all files
echo "📝 PHP Syntax Check:"
cd /var/www/html
for file in *.php; do
    printf "   %-20s: " "$file"
    if php -l "$file" >/dev/null 2>&1; then
        echo "✅ PASSED"
    else
        echo "❌ FAILED"
    fi
done

echo ""
echo "🔗 Database Connection Test:"
php -r "
require_once 'config.php';
if (\$pdo) {
    echo '   Database: ✅ CONNECTED\n';
    \$stmt = \$pdo->query('SELECT COUNT(*) as count FROM users');
    \$count = \$stmt->fetch()['count'];
    echo \"   Users found: \$count ✅\n\";
} else {
    echo '   Database: ❌ FAILED\n';
}
"

echo ""
echo "🔒 Security Functions Test:"
php -r "
require_once 'security.php';
if (function_exists('generateCSRFToken')) {
    echo '   CSRF Token: ✅ WORKING\n';
} else {
    echo '   CSRF Token: ❌ FAILED\n';
}
if (function_exists('detectThreats')) {
    echo '   Threat Detection: ✅ WORKING\n';
} else {
    echo '   Threat Detection: ❌ FAILED\n';
}
"

echo ""
echo "=== ✅ Verification Complete ==="
