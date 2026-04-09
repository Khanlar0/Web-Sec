```markdown
# 🛒 E-Commerce Security Assessment & Penetration Testing Lab

> **Disclaimer:** This project was developed solely for educational purposes and academic assessment. The intentional vulnerabilities included in this repository are meant to demonstrate penetration testing techniques in a safe, local environment.

**Author:** [Senin Adın Soyadın]  
**Student ID:** [Öğrenci Numaran]  
**Date:** April 2026  

---

## 📑 Executive Summary
The objective of this project was to develop a functional local e-commerce web application ("Forma Dünyası") and conduct a comprehensive security assessment to identify potential vulnerabilities. The application allows users to browse products, use a dynamic JavaScript-based shopping cart, and place orders stored in a MySQL database. 

During the penetration testing phase, **TWO CRITICAL vulnerabilities** were discovered:
1. **Authentication Bypass via SQL Injection (SQLi)** in the admin login panel.
2. **Stored Cross-Site Scripting (XSS)** via the shopping cart checkout process.

This report details the methodology, technical exploitation, business impact, and recommended remediation strategies for both vulnerabilities.

---

## 🛠️ Environment & Tech Stack
The testing was conducted in a safe, isolated local environment (`localhost`).

- **Web Server:** Apache (via XAMPP)
- **Database:** MySQL
- **Backend:** PHP 8.x
- **Frontend:** HTML5, CSS3, Vanilla JavaScript

---

## 🚨 Vulnerability 1: Authentication Bypass (SQL Injection)

### 1.1. Description of the Flaw
The application accepts user input from the login form and concatenates it directly into the database query without sanitization. 

**Vulnerable PHP Code (`login.php`):**
```php
$admin_sorgu = "SELECT * FROM admin WHERE kullanici_adi = '$email_veya_kullanici' AND sifre = '$sifre'";
```

### 1.2. The Exploit (Proof of Concept)
A standard payload (`' OR '1'='1`) fails due to SQL Operator Precedence (`AND` is evaluated before `OR`). To bypass this, a SQL comment symbol (`#`) was injected to truncate the password verification logic.

**Injected Payload (Username Field):**
```sql
' OR '1'='1'#
```

**Execution Flow:**
When the payload is injected, the database evaluates: 
`SELECT * FROM admin WHERE kullanici_adi = '' OR '1'='1'#' AND sifre = ''`
Since `'1'='1'` is mathematically TRUE and the `#` comments out the rest of the query, the database ignores the password requirement entirely and grants administrative access.

### 1.3. Remediation (The Patch)
Implement **Prepared Statements** to separate user input from SQL logic.

```php
$stmt = $baglanti->prepare("SELECT * FROM admin WHERE kullanici_adi = ? AND sifre = ?");
$stmt->bind_param("ss", $kullanici, $sifre);
$stmt->execute();
```

---

## ☢️ Vulnerability 2: Stored Cross-Site Scripting (XSS)

### 2.1. Description of the Flaw
The shopping cart relies on client-side JavaScript to compile order details. An attacker can manipulate the browser console to inject malicious scripts into the order payload. The backend stores this payload in the database and renders it in the Admin Dashboard (`admin.php`) without output encoding.

**Vulnerable PHP Code (`admin.php`):**
```php
// The 'detaylar' column is rendered directly as HTML without sanitization
echo "<td>" . $satir['detaylar'] . "</td>";
```

### 2.2. The Exploit (Proof of Concept)
The attacker manipulates the cart data array directly via the browser's Developer Tools (Console) and pushes a malicious payload disguised as a product.

**Injected JavaScript Payload (Browser Console):**
```javascript
sepet.push({ isim: "<script>alert('XSS HÜCUMU: Admin Paneli Ələ Keçirildi! ☠️');</script>", fiyat: 0 });
```

**Execution Flow:**
1. The modified array is sent via a `POST` request to `sifaris_qeyd.php`.
2. The payload is saved permanently in the `sifarisler` database table as an order detail.
3. When the Administrator logs in and views the Orders tab, the browser reads the `<script>` tags as native code rather than text. 
4. The code executes automatically, triggering the alert box on the Admin's screen.

### 2.3. Business and Security Impact
While the Proof of Concept uses a harmless `alert()`, a real-world attacker could use `document.cookie` within the script to steal the administrator's active session token. This would lead to complete account takeover, allowing the attacker to bypass the login screen entirely and manipulate the platform from their own machine.

### 2.4. Remediation (The Patch)
All user-generated content must be sanitized before being rendered in the browser. PHP's `htmlspecialchars()` function must be used to convert special HTML characters into safe entities.

**Secured Code Implementation:**
```php
// The script tags are converted to safe text (e.g., < becomes &lt;)
echo "<td>" . htmlspecialchars($satir['detaylar'], ENT_QUOTES, 'UTF-8') . "</td>";
```
```
