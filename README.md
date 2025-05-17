## 🛠️ Installation Guide

### 🔹 **Step 1: Clone the Repository**
```sh
git clone https:

### 🔹 **Step 2: Install Dependencies**
```sh
composer install
npm install
```

### 🔹 **Step 3: Environment Setup**
```sh
cp .env.example .env
php artisan key:generate
```
Update `.env` with database credentials.

### 🔹 **Step 4: Database Configuration**
```sh
php artisan migrate --seed
```
Import `database/` into your database manually (if needed).

### 🔹 **Step 5: Setup Storage**
```sh
php artisan storage:link
```

### 🔹 **Step 6: Run the Application**
```sh
php artisan serve
```
🔗 Open `http://localhost:8000`