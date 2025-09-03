#!/bin/bash

echo "=== 🚀 Project Delta - Docker Optimization Rebuild ==="
echo ""

# Stop current containers
echo "🛑 Stopping current containers..."
docker-compose down

# Remove old images to force rebuild
echo "🗑️  Removing old images..."
docker rmi entornolamp-web entornolamp-db 2>/dev/null || echo "   Images already removed"

# Clear build cache
echo "🧹 Clearing Docker build cache..."
docker builder prune -f

# Rebuild with optimizations
echo "🔨 Building optimized containers..."
docker-compose build --no-cache

# Start optimized stack
echo "🚀 Starting optimized stack..."
docker-compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 30

# Check health status
echo "🏥 Checking service health..."
docker-compose ps

echo ""
echo "=== ✅ Optimization Complete ==="
echo "📊 Performance improvements:"
echo "   • PHP OPcache enabled"
echo "   • MySQL query cache optimized" 
echo "   • Better dependency management"
echo "   • Optimized resource limits"
echo ""
echo "🌐 Access your optimized application:"
echo "   • Dashboard: http://localhost:8080"
echo "   • phpMyAdmin: http://localhost:8081"
