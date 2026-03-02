# Missing Persons CTF Platform

A Laravel-based CTF platform for running missing persons investigation events. Built with Filament PHP for the admin interface.

⚠️ **Early Development**: This project is in active early development. Expect rapid changes and breaking updates between versions.

## Features

### Current Capabilities
- **Role-Based Access Control** (RBAC) using Spatie Laravel Permission
- **Admin CRUD Operations** via Filament PHP interface
- **Complete Audit Trails** for all model types (using laravel-auditing)
- **Team Management** with captains and coaches
- **Event Management** including time-based events (current/future/past)
- **Case Management** linked to events
- **Submission System** with status tracking (pending/approved/rejected)
- **Ticket Purchase System** with unclaimed ticket handling
- **Self-Registration** with ticket claim functionality
- **Live Event View** for in-progress CTF events

### Simulation Mode (Optional)
When enabled, automatically generates realistic activity for in-progress events:
- Periodic submission generation
- Automated coach decisions on pending submissions

## Technology Stack

- **Backend**: Laravel 12.x, PHP 8.2+
- **Admin Interface**: Filament PHP 4.x
- **Frontend**: Livewire 3.x, TailwindCSS, Vite
- **Database**: MySQL
- **Authentication**: Laravel Jetstream, Sanctum
- **Authorization**: Spatie Laravel Permission
- **Auditing**: Owen-IT Laravel Auditing, Tapp Filament Auditing
- **Testing**: Pest PHP

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL database

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/jefc1111/ctf_app.git
    cd ctf_app
    ```
2. Install PHP dependencies:
    ```bash
    composer install
    ```
3. Install and build frontend assets:
    ```bash
    npm install
    npm run build
    ```
4. Environment setup:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5. Configure your .env file with:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

    ### Required for seeding (set these before running migrations)
    ```env
    INITIAL_ADMIN_PASSWORD=your_secure_admin_password
    TEST_USER_PASSWORD=your_secure_test_password
    ```

    ### Optional: Adjust number of test participants (defaults to 1002)
    ```env
    QTY_TEST_PARTICIPANTS=1002
    ```

    ### Simulation mode (optional)
    ```env
    SIMULATION_MODE=false
    ```
6. Run database migrations and seeders:
    ```bash
    php artisan migrate:fresh --seed
    ```
    This creates:

    - Users of all types (admin, coach, participant)
    - Teams with assigned captains, some with coaches
    - Events (including one starting "now") with assigned cases
    - Submissions with various statuses
    - Unclaimed ticket purchases

7. Start the development server:    
    ### Quick setup (runs all necessary services)
    ```bash
    composer run dev
    ```
    ### Or manually start components:
    ```bash
    php artisan serve
    php artisan queue:listen # Only needed if using simulation mode
    php artisan schedule:work # Only needed if using simulation mode
    ```
8. Usage
    
    Run migrations and seed the database:
    ```bash
    php artisan migrate:fresh --seed
    ```

## Simulation Mode
To enable automated activity generation:

Set `SIMULATION_MODE=true` in `.env`

For events with `simulate_activity=true`, the system will:

- Periodically generate submissions
- Auto-process pending submissions

## Development with hot reload
```bash
npm run dev
```

## Contributors
As this is early in development, please:

1. Fork the repository
2. Create a feature branch
3. Submit a pull request

### License
MIT License - as specified in composer.json

### Support
For issues, questions, or contributions, please visit the GitHub repository