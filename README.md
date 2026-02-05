# Learning Management System (LMS)

A comprehensive Learning Management System built with CodeIgniter 4. This application allows for course management, student enrollment, instructor dashboards, and online learning capabilities.

## Technologies Used

### Backend

- **Framework**: [CodeIgniter 4](https://codeigniter.com/)
- **Language**: PHP 8.2+
- **Database**: MySQL

### Frontend

- **CSS Framework**: Bootstrap 5
- **Icons**: FontAwesome
- **Scripting**: JavaScript / jQuery

## Installation & Setup

Follow these steps to set up the project locally:

1.  **Clone the Repository**

    ```bash
    git clone <repository_url>
    cd newlms
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    ```

3.  **Environment Configuration**
    - Copy `env` to `.env`.
    - Open `.env` and set your database connection details and base URL.

    ```ini
    CI_ENVIRONMENT = development
    app.baseURL = 'http://localhost:8080/'

    database.default.hostname = localhost
    database.default.database = newlms
    database.default.username = root
    database.default.password =
    database.default.DBDriver = MySQLi
    ```

4.  **Database Setup**
    Run the migrations to create the database tables:
    ```bash
    php spark migrate
    ```
    (Optional) Seed the database with initial data:
    ```bash
    php spark db:seed UserSeeder
    ```

## Running the Application

To start the local development server:

```bash
php spark serve
```

Access the application in your browser at `http://localhost:8080`.

## Project Documentation

detailed guides for different user roles can be found here:

- [User Guide (Student)](docs/UserGuide.md)
- [Instructor Guide](docs/InstructorGuide.md)

## License

This project is licensed under the MIT License.
