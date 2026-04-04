Penetration Testing and Vulnerability Assessment Report
Project Title: Local E-Commerce Application Security Assessment
Student Name: Xanlar Rustamov
Group: 693.24
Date: April 4, 2026
1. Executive Summary
The objective of this project was to develop a functional local e-commerce web application ("FormaX.az") and conduct a security assessment to identify potential vulnerabilities. The application allows users to browse football jerseys, add them to a dynamic cart, and place orders which are recorded in a MySQL database. During the penetration testing phase, a critical SQL Injection (SQLi) vulnerability was discovered in the administrator login panel. This report details the methodology, the technical exploitation of the vulnerability, its potential impact, and recommended remediation strategies.
 
2. Methodology and Environment Setup
The testing was conducted in a safe, isolated local environment to simulate a real-world web server architecture.
•	Web Server Architecture: Apache (via XAMPP)
•	Database Management: MySQL (via phpMyAdmin)
•	Core Technologies: PHP, HTML, CSS, JavaScript
•	Testing Approach: Black-box / Gray-box testing simulation on the localhost environment.
 
3. System Overview
The target application simulates a standard e-commerce workflow, consisting of:
1.	Frontend Interface: Displays products (Sabah, Qarabağ, Neftçi jerseys) and includes a JavaScript-powered shopping cart.
2.	Backend Processing: A PHP script (sifaris_qeyd.php) handles incoming asynchronous requests and inserts order details into the sifarisler database table.
3.	Administrator Panel: A restricted dashboard (admin.php) for store managers to view real-time customer orders, protected by an authentication page (login.php).
4. Vulnerability Assessment: SQL Injection (SQLi)
•	Vulnerability Type: Authentication Bypass via SQL Injection
•	Severity: CRITICAL
•	Location: Administrator Login Panel (http://localhost/forma_sayti/login.php)
•	Vulnerable Parameter: Username input field (kullanici_adi)
4.1. Description of the Flaw
The vulnerability exists because the application accepts user input from the login form and concatenates it directly into the database query without sanitization. The vulnerable PHP code block in login.php is written as follows:
 
4.2. Exploitation and Proof of Concept (PoC)
To demonstrate the vulnerability, an authentication bypass was performed. Initially, a standard payload [' OR '1'='1] was attempted, but it failed due to SQL Operator Precedence (the AND operator is evaluated before the OR operator, causing the password check to invalidate the query).
To successfully bypass this, the payload was modified to include a SQL comment symbol [#] to truncate the rest of the query.
Execution Steps:
1.	Navigated to the Admin Login page.
2.	Inserted the following malicious payload into the Username field: [ ' OR '1'='1'# ]
3.	Left the password field blank and executed the login request.
 
Technical Explanation of the Exploit: When the modified payload is injected, the backend database query transforms into:
 
The # symbol comments out the remainder of the SQL statement (AND sifre = ''). The database engine only evaluates [ kullanici_adi = '' OR '1'='1']. Since the mathematical statement ['1'='1'] is always TRUE, the database ignores the password requirement entirely and grants administrative access, successfully bypassing the authentication mechanism.
 
5. Business and Security Impact
If this vulnerability existed in a live production environment, the consequences would be severe:
•	Data Breach: Attackers could gain unauthorized access to the admin panel and view sensitive customer order data, leading to a breach of privacy.
•	Loss of Integrity: An attacker could potentially modify database structures, alter prices, or delete legitimate customer orders.
•	System Compromise: This flaw represents a complete breakdown of the application's access control mechanisms.
6. Remediation and Solution
To secure the application against SQL Injection, the source code must be updated to separate user input from SQL logic. The most effective defense is the implementation of Prepared Statements (Parameterized Queries).
Recommended Code Patch (PHP/MySQLi):
 
 
By utilizing prepared statements, the database engine treats the user input strictly as literal string data rather than executable SQL commands, rendering injection payloads completely ineffective.

