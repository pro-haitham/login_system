# login_system

/login_system

│
├── includes/

│ ├── db.php # Handles the database connection.

│ └── functions.php # Placeholder for reusable functions (currently empty).

│

├── dashboard.php # A protected page accessible only to logged-in users.

├── login.php # The user login page and authentication handler.

├── logout.php # Destroys the user session to log them out.

├── signup.php # Intended for user registration (currently contains logout logic).

└── style.css # For CSS styling (currently empty).

File Purposes
includes/db.php: Establishes a connection to the MySQL database using defined credentials. It's included in any file that needs to interact with the database.

includes/functions.php: This file is intended to hold common, reusable PHP functions to avoid code repetition. It is currently empty but included in login.php.

login.php: Serves as the main entry point for users. It displays the login form and contains the server-side logic to handle form submissions, validate user credentials, and implement security measures like rate limiting.

dashboard.php: A restricted-access page. It checks for an active user session and redirects to the login page if the user is not authenticated.

logout.php / signup.php:

The file named signup.php contains the logic for logging a user out by destroying their session.

The file named logout.php is empty. For this system to function correctly, the code from signup.php should be moved to logout.php. The signup.php file would then need to be implemented with a user registration form and logic.

style.css: A placeholder for styling the HTML pages.

2. Core Components
The system is built around a few key PHP scripts that manage sessions, database interactions, and user authentication.

db.php - Database Connector
Variables: $servername, $username, $password, $dbname hold the database connection details.

Connection Object: Creates a global mysqli object named $conn that represents the connection to the MySQL database.

Error Handling: Includes a basic check ($conn->connect_error) that terminates the script (die()) if the connection fails, providing a clear error message.

login.php - Authentication Handler
This is the most complex component, responsible for both displaying the login form and processing login attempts.

Session Management: Starts or resumes a session using session_start() to track user login status.

Dependencies: Includes db.php to access the $conn object and functions.php.

Security (Rate Limiting):

It uses session variables ($_SESSION['attempts'], $_SESSION['last_attempt']) to track failed login attempts.

It blocks login attempts for 60 seconds if there are 3 or more failed attempts within that window, which helps mitigate brute-force attacks.

Authentication Logic:

When the form is submitted ($_SERVER["REQUEST_METHOD"] == "POST"), it retrieves the username and password.

It uses a prepared statement ($conn->prepare) to query the database. This is a critical security feature that prevents SQL injection.

It fetches the hashed password from the database for the given username.

It securely verifies the submitted password against the stored hash using password_verify().

State Handling:

On success: It stores the username in the session ($_SESSION['username']), resets the attempt counter, and redirects the user to dashboard.php.

On failure: It provides a user-friendly error message ("Incorrect password!" or "Username not found!") and increments the failed attempt counter.

dashboard.php - Protected Content
This component acts as a gatekeeper for protected content.

Access Control: Its first action is to check if $_SESSION['username'] is set. If not, the user is not logged in, and the script immediately redirects them to login.php.

Content Display: If the session exists, it safely displays the username (using htmlspecialchars() to prevent Cross-Site Scripting (XSS) attacks) and provides a link to log out.

logout.php - Session Terminator
This script handles the logout process.

Session Destruction: It unsets all session variables with session_unset() and then completely destroys the session with session_destroy().

Redirection: After destroying the session, it redirects the user back to login.php.

3. Functionality
The components work together to create a standard authentication workflow.

Initial Visit: A user accesses login.php and is presented with a username and password form.

Credential Submission: The user submits the form. The data is sent via POST back to login.php.

Validation & Verification:

The login.php script checks for rate limiting. If the user has failed too many times recently, access is denied.

The script securely queries the login_demo database for the submitted username.

If the user exists, password_verify() compares the submitted password with the securely stored hash.

Successful Login:

If credentials are correct, the server creates a session and stores the username in it (e.g., $_SESSION['username'] = 'john_doe').

The user is redirected to dashboard.php.

Accessing Protected Page:

dashboard.php checks if the session variable $_SESSION['username'] exists.

Since it does, the page loads and displays a personalized welcome message.

Logging Out:

The user clicks the "Logout" link, which directs them to logout.php.

The logout.php script destroys the session, effectively "forgetting" the user.

The user is redirected back to login.php.

4. Usage & Extension
To implement or extend this login system, follow these steps.

Setup Instructions
Web Server: Ensure you have a PHP-enabled web server like XAMPP or WAMP running. Place the login_system folder in the server's web root (e.g., C:\xampp\htdocs\).

Database Creation:

Open phpMyAdmin (usually at http://localhost/phpmyadmin).

Create a new database named login_demo.

Table Creation:

Select the login_demo database and run the following SQL query to create the users table:

SQL

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
Create a Test User: To log in, you must insert a user with a hashed password. You cannot insert a plain-text password. Run a simple PHP script (or use an online tool) to generate a hash:

PHP

<?php
// Use this to generate a password hash for your SQL INSERT statement
echo password_hash("your_secure_password", PASSWORD_DEFAULT);
?>
Copy the resulting hash and use it in an INSERT query:

SQL

-- Example hash for password "password123": $2y$10$... (your hash will be different)
INSERT INTO users (username, password) VALUES ('testuser', '$2y$10$E...your...generated...hash...here');
Run the Application: Navigate to http://localhost/login_system/login.php in your browser.

How to Extend the Code
Implement Signup Page: Create the user registration logic in signup.php. It should include:

An HTML form to collect a new username and password.

PHP code to hash the new password using password_hash().

A prepared INSERT statement to safely add the new user to the database.

Use functions.php: Move repetitive logic into functions.php. For example, a function is_logged_in() could check isset($_SESSION['username']) and return true or false.

Add More Profile Information: Add more columns to the users table (e.g., email, first_name, last_name) and update the signup form and dashboard to use them.

Improve User Feedback: Use session "flash messages" to show success or error messages across different pages (e.g., "You have been logged out successfully" shown on the login page after a redirect).

##Author


Haitham Al-Dabbi
