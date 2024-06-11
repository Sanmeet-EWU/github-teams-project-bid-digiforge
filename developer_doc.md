# DigiForge Developer Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Project Structure](#project-structure)
3. [Codebase Overview](#codebase-overview)
4. [Development Environment Setup](#development-environment-setup)
5. [Deployment Instructions](#deployment-instructions)
6. [Accessing the Admin Panel](#accessing-the-admin-panel)
7. [Database Access](#database-access)

## Introduction
This documentation is intended for developers working on the DigiForge project, a website redevelopment for a local architecture firm in Spokane, WA. It provides an overview of the project structure, codebase, development environment setup, deployment instructions, and access details for the admin panel and database.

## Project Structure
The project is organized as follows:
- `wp-content`: Contains all WordPress content, including themes, plugins, and uploads.
  - `themes`: Custom and default WordPress themes.
  - `plugins`: Plugins used in the project, including the custom contact form notification plugin.
  - `uploads`: Media and file uploads.

- `wp-config.php`: The WordPress configuration file containing database connection details.
- `index.php`: The main entry point for the WordPress application.

## Codebase Overview
The codebase primarily consists of the following components:
1. **WordPress Core**: The foundational code of the WordPress CMS.
2. **Custom Theme**: A custom WordPress theme developed for the architecture firm's website, located in `wp-content/themes/custom-theme`.
3. **Plugins**: Essential plugins, including a custom contact form notification plugin found in `wp-content/plugins/contact-form-notification`.

## Development Environment Setup
To set up the development environment, follow these steps:

1. **Clone the Repository**:
   - Clone the project repository from the version control system.
   - ```sh
     git clone https://github.com/Sanmeet-EWU/github-teams-project-bid-digiforge
     ```

2. **Install Dependencies**:
   - Ensure you have the necessary software installed:
     - PHP (version 7.4 or higher)
     - MySQL or MariaDB
     - Apache or Nginx web server
     - Composer (for PHP dependency management)
   - Install WordPress dependencies:
     - ```sh
       composer install
       ```

3. **Configure the Environment**:
   - Copy the sample configuration file and update it with your local settings:
     - ```sh
       cp wp-config-sample.php wp-config.php
       ```
   - Update the `wp-config.php` file with your database credentials and other configuration settings.

4. **Run the Development Server**:
   - Start your local development server (e.g., using MAMP, XAMPP, or a custom Apache/Nginx setup).
   - Ensure the server is pointing to the project directory.

## Deployment Instructions
To deploy the project, follow these steps:

1. **Prepare the Server**:
   - Ensure the server has PHP, MySQL, and Apache/Nginx installed.
   - Set up the necessary file permissions for WordPress.

2. **Upload Files**:
   - Transfer the project files to the server using FTP, SCP, or another file transfer method.

3. **Configure the Database**:
   - Export the local development database and import it to the server's database.
   - Update the `wp-config.php` file with the server's database credentials.

4. **Finalize the Setup**:
   - Run any necessary database migrations or updates.
   - Ensure the server's document root is set to the project directory.
   - Restart the web server to apply changes.

## Accessing the Admin Panel
To access the WordPress admin panel, use the following credentials:
- Open `http://localhost/wp-admin` in your browser.
- **WP Admin Credentials**:
  - Username: `kkahalekai@ewu.edu`
  - Password: `fusion_architect1234`

## Database Access
The contact form on the website is connected to a database that stores user inquiries. To access and manage this database:

1. **Database Credentials**:
   - Username: `wordpress`
   - Password: `fusion_architect1234`

2. **Access phpMyAdmin**:
   - Navigate to `http://localhost/phpmyadmin` in your browser.
   - Use the provided credentials to log in and manage the database.

## Conclusion
This documentation provides the necessary details for developers to understand the project structure, set up their development environment, deploy the project, and access the admin panel and database. For further assistance, refer to the contact information provided in the user manual.