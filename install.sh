#!/bin/bash

set -e

echo "======================================"
echo "Pet Registration Application - Setup"
echo "======================================"
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker is not running. Please start Docker and try again.${NC}"
    exit 1
fi

echo -e "${BLUE}🐳 Starting Docker containers...${NC}"
docker compose up -d

echo -e "${BLUE}⏳ Waiting for database to be ready...${NC}"
sleep 5

# Wait for database to be healthy
echo -e "${BLUE}🔍 Checking database health...${NC}"
for i in {1..30}; do
    if docker compose exec -T database mysqladmin ping -h localhost --silent; then
        echo -e "${GREEN}✅ Database is ready${NC}"
        break
    fi
    if [ $i -eq 30 ]; then
        echo -e "${RED}❌ Database failed to start${NC}"
        exit 1
    fi
    echo "Waiting for database... ($i/30)"
    sleep 2
done

echo -e "${BLUE}📦 Installing PHP dependencies...${NC}"
docker compose exec -T php composer install --no-interaction

echo -e "${BLUE}📦 Installing Node.js dependencies...${NC}"
docker compose run --rm node npm install

echo -e "${BLUE}🎨 Building Tailwind CSS...${NC}"
docker compose exec -T php bin/console tailwind:build --no-interaction

echo -e "${BLUE}🗄️  Setting up database...${NC}"
docker compose exec -T php bin/console doctrine:database:create --if-not-exists --no-interaction

echo -e "${BLUE}🚀 Running database migrations (includes breed data)...${NC}"
docker compose exec -T php bin/console doctrine:migrations:migrate --no-interaction

echo ""
echo -e "${GREEN}======================================"
echo "✅ Installation complete!"
echo "======================================${NC}"
echo ""
echo -e "${YELLOW}🌐 Application is running at:${NC}"
echo -e "${BLUE}   http://localhost:8080${NC}"
echo ""
echo -e "${YELLOW}📚 Useful commands:${NC}"
echo "   docker compose logs -f          # View logs"
echo "   docker compose exec php bin/phpunit --testdox  # Run tests"
echo "   docker compose down             # Stop containers"
echo "   docker compose up node          # Watch Tailwind changes"
echo ""
