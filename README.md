# Pet Registration Demo

A modern pet registration system built with Symfony 7.3, featuring a dynamic LiveComponent form with real-time validation, breed filtering, and a Tailwind CSS interface.

## Features

- 🐱 🐶 Dynamic pet registration form for cats and dogs
- ⚡ Real-time breed filtering based on pet type (Symfony UX LiveComponent)
- 🎨 Beautiful gradient UI with Tailwind CSS
- ✅ Comprehensive form validation with error displays
- 📅 Flexible age input (exact birth date or approximate age)
- ⚠️ Dangerous breed warnings
- 🎉 Confirmation page with pet details
- 🧪 Test coverage (Unit, Integration, Functional tests)

## Tech Stack

- **PHP 8.4** / **Symfony 7.3**
- **Symfony UX LiveComponent** - Dynamic reactive components
- **Doctrine ORM** - Database abstraction
- **MySQL 8.0** - Database
- **Tailwind CSS** - Styling
- **PHPUnit 12.5** - Testing
- **Docker** - Containerization

## Project Structure

```
src/
├── Controller/         # HTTP controllers (registration, confirmation)
├── Entity/            # Doctrine entities (Pet, Breed)
├── Enum/              # PHP enums (PetType, Gender)
├── Repository/        # Database repositories
├── Twig/Components/   # LiveComponent reactive components
└── DataFixtures/      # Test data fixtures

templates/
└── pages/             # Twig templates

tests/
├── Entity/            # Unit tests
├── Twig/Components/   # Integration tests (KernelTestCase)
└── Controller/        # Functional tests (WebTestCase)
```

## Getting Started

### Prerequisites

- Docker & Docker Compose
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd symfony-regitration
   ```

2. **Start Docker containers**
   ```bash
   docker compose up -d
   ```

3. **Install PHP dependencies**
   ```bash
   docker compose exec php composer install
   ```

4. **Install Node.js dependencies and build assets**
   ```bash
   docker compose exec node npm install
   docker compose exec node npm run build
   ```

5. **Set up the database**
   ```bash
   # Create database
   docker compose exec php bin/console doctrine:database:create

   # Run migrations
   docker compose exec php bin/console doctrine:migrations:migrate --no-interaction

   # Load breed fixtures (optional)
   docker compose exec php bin/console doctrine:fixtures:load --no-interaction
   ```

6. **Access the application**

   Open your browser and navigate to: **http://localhost:8080**

## Development

### Running Tests

```bash
# Run all tests
docker compose exec php bin/phpunit

# Run with testdox format (readable output)
docker compose exec php bin/phpunit --testdox

# Run specific test suite
docker compose exec php bin/phpunit tests/Entity
docker compose exec php bin/phpunit tests/Twig/Components
docker compose exec php bin/phpunit tests/Controller
```

### Watching Assets (Development)

```bash
# Watch for changes and rebuild automatically
docker compose exec node npm run watch
```

### Database Commands

```bash
# Create a new migration
docker compose exec php bin/console make:migration

# View database schema
docker compose exec php bin/console doctrine:schema:validate

# Reset database (WARNING: destroys all data)
docker compose exec php bin/console doctrine:database:drop --force
docker compose exec php bin/console doctrine:database:create
docker compose exec php bin/console doctrine:migrations:migrate --no-interaction
```

## Testing

The application has test coverage across three layers:

- **Unit Tests (7 tests)**: Entity logic (Pet, Breed)
- **Integration Tests (14 tests)**: Component behavior with services (Registration LiveComponent)
- **Functional Tests (10 tests)**: HTTP request/response cycle (Controllers)

**Total: 31 tests, 100+ assertions**

Run tests:
```bash
docker compose exec php bin/phpunit --testdox
```

## Environment Configuration

Key environment variables in `.env`:

```env
APP_ENV=dev
DATABASE_URL="mysql://symfony:symfony@database:3306/symfony"
```

For testing, `.env.test` is used with a separate test database.

## Docker Services

- **php** (symfony_registration_php) - PHP 8.4 with Symfony application
- **nginx** (symfony_registration_nginx) - Web server on port 8080
- **database** (symfony_registration_db) - MySQL 8.0 on port 3306
- **node** (symfony_registration_node) - Node.js 20 for asset building

## Dangerous Dog Breeds

The following breeds are flagged as dangerous in the system:
- Belgian Malinois
- Boxer
- Bullmastiff
- Dalmatian
- German Shepherd
- Husky
- Pit Bull
- Rottweiler

## License

Proprietary
