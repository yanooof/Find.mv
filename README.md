# Find.mv

**Find.mv** is a Laravel-based web application that allows users in **Malé City, Maldives** to search for products across multiple local retail stores in one place.  
It aggregates and displays up-to-date product information (name, price, store, and link to the original page) collected via custom Python web scrapers.

---

## Overview

The aim of this project is to make local shopping more efficient by bridging the gap between online browsing and in-store availability.  
Instead of visiting multiple local store websites, users can simply search once to find:

-   Which **stores** have the item in stock
-   The **price** at each store
-   A direct **link** to the store’s product page

This saves time and improves visibility of local retail options.

---

## Features

-   **Unified Search Interface**  
    Google-style homepage with a single search bar.

-   **Dark Themed UI**  
    Modern dark mode with gradient background and accent highlights.

-   **Real-Time Local Data**  
    Product data scraped directly from multiple Maldivian stores, currently using data from:

    -   [Sonee Hardware](https://www.sonee.com.mv)
    -   [Asters Online](https://www.astersonline.com)
    -   [LinkServe Shop](https://shop.linkserve.mv)

-   **Smart Price Normalization**  
    All prices are standardized to `MVR` format for consistency.

-   **Store Filtering**  
    Quickly narrow search results by selecting specific stores.

-   **Paginated Results**  
    Results are cleanly paginated for faster performance.

-   **SQLite Integration**  
    Lightweight database shared between the Laravel app and Python scrapers.

---

## Technology Stack

| Layer               | Tools / Frameworks                                                 |
| :------------------ | :----------------------------------------------------------------- |
| **Frontend**        | Laravel Blade Components, TailwindCSS / Bootstrap-style custom CSS |
| **Backend**         | Laravel 11 (PHP 8+)                                                |
| **Scraping**        | Python 3.13, Selenium, WebDriverManager                            |
| **Database**        | SQLite (replaces MySQL for simplicity)                             |
| **Version Control** | Git + GitHub                                                       |
| **Environment**     | Windows 10 / 11, Visual Studio Code                                |

---

## System Workflow

1. **Data Collection:**  
   Python Selenium scripts crawl local e-commerce sites page by page or via “load more” interactions, collecting product name, price, image, and link.

2. **Data Storage:**  
   Each scraper inserts results into a shared SQLite database using `sqlite3`, keeping timestamps for freshness.

3. **Data Presentation:**  
   Laravel reads from the same database via Eloquent ORM and renders the results dynamically through reusable Blade components.

---

## ⚒️ Setup & Installation

### 1. Clone the repository

git clone https://github.com/yourusername/product-finder.git
cd product-finder

### 2. install php and laravel dependencies

composer install
cp .env.example .env
php artisan key:generate

### 3. Configure DB

DB_CONNECTION=sqlite
DB_DATABASE=database/db.sqlite

### 4. Run the server

php artisan serve

Now open http://localhost:8000 to view the app.
