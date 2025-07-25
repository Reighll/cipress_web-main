# POS Inventory System

## Project Name
POS Inventory System

## Client
Nj Cipres General Merchandise



## 1. Project Description

The Hardware POS and Inventory System is a desktop or web-based application designed to streamline the sales, inventory tracking, and overall business operations of a hardware store. This system aims to automate transactions, efficiently manage product stock levels, and generate essential business reports, providing real-time insights for both cashiers (staff) and store managers (owners).

**Key Features:**
* **Dual Access Levels:** Distinct functionalities for Owner and Staff roles.
* **Owner Dashboard:** Overview of staff accounts needing approval and a system key generator for new owner accounts.
* **Inventory Management:** Comprehensive listing, viewing, adding, editing, and updating of stock items (No., Name, Category, Quantity, Price).
* **Staff Management:** Monitoring staff activity (clock-in/out) and managing staff accounts (edit/delete).
* **Sales Reporting:** Generation of daily, weekly, monthly, and annual sales reports.
* **Point of Sale (POS) for Staff:**
    * **Cart View:** Input customer information, view available stocks, add/remove items to/from cart.
    * **Payment View:** Review cart items, confirm total price, input payment received, and calculate change.
    * **Transaction View:** Automated receipt generation with purchased items, total price, payment received, and change.

## 2. Installation Instructions

This section outlines the steps to get the POS Inventory System installed and set up on a local machine.

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/Reighll/cipress_web-main.git](https://github.com/Reighll/cipress_web-main.git)
    ```

2.  **Navigate to the project directory:**
    ```bash
    cd cipress_web-main
    ```

3.  **Install PHP dependencies (if applicable, for CodeIgniter 4):**
    ```bash
    composer install
    ```
    *(Ensure Composer is installed on your system.)*

4.  **Database Setup (MySQL 8.0):**
    * Create a MySQL database (e.g., `pos_inventory_db`).
    * Import your database schema and initial data.
        *(Instructions for database import, e.g., `mysql -u your_user -p pos_inventory_db < database.sql` or using a GUI tool like phpMyAdmin/MySQL Workbench)*

5.  **Configure Environment Variables:**
    * Create a `.env` file by copying `.env.example` (if using CodeIgniter 4).
    * Update database connection details in your `.env` file:
        ```
        database.default.hostname = localhost
        database.default.database = cipress_web
        database.default.username = root
        database.default.password = 1234
        ```


## 3. Usage Guide

Instructions on how to use the application after it's installed.

1.  **Start the local development server (if web-based, using CodeIgniter's spark server):**
    ```bash
    php spark serve
    ```
    *(This will typically start the server on `http://localhost:8080` or similar.)*

2.  **Open your web browser and navigate to the application URL:**
    ```
    http://localhost:8080
    ```
    *(Adjust the port if your server starts on a different one.)*

3.  **Log in to the system:**
    * **Default Owner Credentials (if applicable):**
        * Username: `admin@example.com` (or similar)
        * Password: `password` (or similar)
        *(It is highly recommended to change these default credentials immediately after the first login for security reasons.)*

## 4. Supported Operating Systems

The system is designed to run on the following operating systems:
* Windows 10
* Windows 11

## 5. Technology Stack

* **Programming Language:** PHP 8.3.21
* **Framework:** CodeIgniter 4
* **Frontend Framework/Library:** Bootstrap
* **Database:** MySQL 8.0

## 6. Development Tools

The following tools were used during development:
* PHPStorm
* Git
* Github
