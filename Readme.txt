
# 💰 PayChanguDemo – Simple Payment Integration in PHP by Yona Masia 0888842289

This is a sample PHP application to demonstrate how to integrate **PayChangu** payments using their API. It allows users to submit a payment, verify it, and save transaction details into a database.

---

## 📂 Project Structure

```
paychangudemo/
│
├── config.php                 # Database connection (PDO)
├── index.php                 # User input form (name, email, amount)
├── process_payment.php       # Sends payment to PayChangu API
├── verify_transaction.php    # Verifies payment status & shows styled receipt
├── thankyou.php              # Optional post-payment return page
├── webhook.php               # Handles webhook notifications from PayChangu
├── transactions.sql          # SQL for creating transactions table
└── ngrok.exe                 # Optional: for local webhook testing
```

---

## 🚀 Getting Started

### 1. Clone or Download the Project
```bash
git clone https://github.com/your-username/paychangudemo.git
cd paychangudemo
```

### 2. Set Up Database

- Open `transactions.sql` in phpMyAdmin or MySQL and run it to create the required table:
```sql
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tx_ref VARCHAR(100) UNIQUE,
    customer_email VARCHAR(100),
    amount INT,
    currency VARCHAR(10),
    status VARCHAR(50),
    payment_type VARCHAR(50),
    channel VARCHAR(50),
    reference VARCHAR(100),
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Configure Database

Open `config.php` and set your database connection:

```php
$pdo = new PDO("mysql:host=localhost;dbname=your_db", "username", "password");
```

### 4. Start Local Server

If using WAMP/XAMPP:
- Place the folder in `www` or `htdocs`
- Open `http://localhost/paychangudemo/` in your browser

---

## 🌐 PayChangu Setup

1. Go to [PayChangu Dashboard](https://paychangu.com)
2. Get your **Test or Live Secret Key**
3. Replace it in:
   - `process_payment.php`
   - `verify_transaction.php`
   - `webhook.php`

```php
$secret_key = "SEC-xxxxxx"; // Replace this
```

---

## 📦 Optional: Webhook Testing with Ngrok

Use `ngrok` to expose your local webhook endpoint:

```bash
./ngrok.exe http 80
```

Then copy the URL (e.g., `https://b73f-xxx.ngrok-free.app`) and update:
- `callback_url`
- `return_url`  
in `process_payment.php`

---

## 🎯 Features

- ✅ User enters amount, name, and email
- ✅ Redirects to PayChangu Checkout
- ✅ Verifies transaction via API
- ✅ Saves transaction in MySQL
- ✅ Receipt page styled with Tailwind CSS
- ✅ Print & PDF download buttons
- ✅ Webhook support for background status updates

---

## 📸 Sample Screenshot

*Insert screenshot of the receipt page here*

---

## 🙌 Credits

- [PayChangu](https://paychangu.com)
- Tailwind CSS CDN
- PHP & MySQL

---

## 📃 License

This project is free to use and modify for learning and integration purposes.
