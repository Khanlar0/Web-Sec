# 🛒 E-Commerce SQL Injection Lab & Security Assessment

> **Disclaimer:** This project was developed solely for educational purposes and academic assessment. The intentional vulnerabilities included in this repository are meant to demonstrate penetration testing techniques in a safe, local environment.

**Author:** Xanlar Rsutamov  
**Date:** April 2026  

---

## 📑 Executive Summary
The objective of this project was to develop a functional local e-commerce web application ("Forma Dünyası") and conduct a security assessment to identify potential vulnerabilities. The application allows users to browse products, use a dynamic shopping cart, and place orders stored in a MySQL database. 

During the penetration testing phase, a **CRITICAL Authentication Bypass via SQL Injection (SQLi)** vulnerability was discovered in the administrator login panel. This report details the methodology, the technical exploitation, and the recommended remediation strategies.

---

## 🛠️ Environment & Tech Stack
The testing was conducted in a safe, isolated local environment (`localhost`) to simulate a real-world web server architecture.

- **Web Server:** Apache (via XAMPP)
- **Database:** MySQL
- **Backend:** PHP 8.x
- **Frontend:** HTML5, CSS3, Vanilla JavaScript

---

## 🚨 Vulnerability Assessment: SQL Injection (SQLi)

### 1. Description of the Flaw
The vulnerability exists because the application accepts user input from the login form and concatenates it directly into the database query without sanitization. 

**Vulnerable PHP Code (`login.php`):**
```php
$sorgu = "SELECT * FROM admin WHERE kullanici_adi = '$kullanici' AND sifre = '$sifre'";
$sonuc = $baglanti->query($sorgu);
```

### 2. The Exploit (Proof of Concept)
To demonstrate the vulnerability, an authentication bypass was performed. A standard payload (`' OR '1'='1`) would normally fail due to **SQL Operator Precedence** (the `AND` operator is evaluated before `OR`, causing the password check to invalidate the query). 

To successfully bypass this, a SQL comment symbol (`#`) was injected to truncate the rest of the query.

**Injected Payload (Username Field):**
```sql
' OR '1'='1'#
```

**How the Database Reads It:**
When the payload is injected, the backend database query transforms into:
```sql
SELECT * FROM admin WHERE kullanici_adi = '' OR '1'='1'#' AND sifre = ''
```
The `#` symbol comments out the remainder of the SQL statement (`AND sifre = ''`). Since `'1'='1'` is mathematically TRUE, the database ignores the password requirement entirely and grants administrative access.

### 3. Post-Exploitation (Admin Panel Access)
Upon successful injection, the system grants access to `admin.php`. Without knowing any credentials, the attacker can view the highly sensitive `sifarisler` (Orders) database table in real-time.

**Simulated Admin Dashboard View:**
| ID | Order Details | Total Amount (AZN) | Date |
|:---|:---|:---|:---|
| 3 | Neftçi Retro Forma (90 AZN), | 90 | 2026-04-04 15:30:00 |
| 2 | Sabah Ev Forması (60 AZN), Qarabağ Səfər (65 AZN), | 125 | 2026-04-04 15:28:45 |
| 1 | Qarabağ Səfər Forması (65 AZN), | 65 | 2026-04-04 15:25:10 |

---

## 💥 Business and Security Impact
If this vulnerability existed in a live production environment, the consequences would be severe:
1. **Data Breach:** Attackers gain unauthorized access to view sensitive customer order data.
2. **Loss of Integrity:** An attacker could modify database structures, alter prices, or delete legitimate orders.
3. **Total Compromise:** Represents a complete breakdown of the application's access control mechanisms.

---

## 🛡️ Remediation and Solution (The Patch)
To secure the application against SQL Injection, the source code must be updated to separate user input from SQL logic. The most effective defense is the implementation of **Prepared Statements**.

**Secured Code Implementation:**
```php
// Secure implementation using Prepared Statements (MySQLi)
$stmt = $baglanti->prepare("SELECT * FROM admin WHERE kullanici_adi = ? AND sifre = ?");
$stmt->bind_param("ss", $kullanici, $sifre);
$stmt->execute();
$sonuc = $stmt->get_result();
```
By utilizing prepared statements, the database engine treats the user input strictly as literal string data rather than executable SQL commands, rendering injection payloads completely ineffective.
