# NexProcure

A **Product, Supplier, and Purchase Management System** built with **Laravel**.

## Overview

NexProcure is a lightweight and scalable management system designed to streamline business operations related to **products, suppliers, and purchases**.
It helps businesses manage their supply chain efficiently with features like supplier management, purchase tracking, and inventory organization.

## Features

-   **Product Management** – Add, update, and manage product details.
-   **Supplier Management** – Maintain supplier records with contact info and status.
-   **Purchase Management** – Track purchase orders, statuses (`pending`, `completed`, `cancelled`).
-   **Dashboard** – Quick overview of suppliers, products, and purchases.
-   **Search & Filter** – Find suppliers or products easily.
-   **Export Options** – Export purchase or supplier data to **Excel/PDF**.
-   **Authentication** – Secure login and role-based access.

## Tech Stack

-   **Backend:** Laravel 10+
-   **Frontend:** Blade, TailwindCSS, Alpine.js
-   **Database:** MySQL
-   **Authentication:** Laravel Breeze / Jetstream
-   **Exporting:** PhpSpreadsheet / DomPDF

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/nexprocure.git
    cd nexprocure
    ```

2. Install dependencies:

    ```bash
    composer install
    npm install && npm run dev
    ```

3. Configure `.env` file:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Set up database and run migrations:

    ```bash
    php artisan migrate --seed
    ```

5. Serve the application:

    ```bash
    php artisan serve
    ```

    Visit: [http://localhost:8000](http://localhost:8000)

## Roadmap

-   [ ] Add product categories
-   [ ] Role-based permissions (Admin, Staff)
-   [ ] Supplier payment tracking
-   [ ] REST API support
-   [ ] Advanced reporting & analytics

## Contributing

Contributions are welcome! Feel free to fork this repo and submit pull requests.

## License

This project is licensed under the **MIT License**.
