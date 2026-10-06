# Care-Box

## Project Overview
Care-Box is a web-based community donation platform connecting donors with individuals and organizations in need. It features interactive donation stories, secure authentication, and a dedicated donor dashboard to facilitate and track charitable contributions.

## Key Features
* User Authentication: Secure sign-up and login functionality for donors and recipients.
* Donor Dashboard: A dedicated space for donors to manage their profiles, track past donations, and view impact.
* Interactive Donation Stories: Engaging narratives highlighting the needs of individuals and organizations.
* Recipient Management: Tools for organizations and individuals to post their needs and receive contributions.
* Secure Platform: Ensures data privacy and safe connections between donors and recipients.

## Tech Stack
* Frontend: HTML5, CSS3, JavaScript
* Backend: PHP
* Database: MySQL

## Database Setup Instructions
1. Open your local server environment (such as XAMPP, WAMP, or MAMP) and ensure both Apache and MySQL services are running.
2. Navigate to phpMyAdmin in your web browser (typically at http://localhost/phpmyadmin).
3. Create a new database named for the project (e.g., care_box).
4. Import the provided SQL file (if available in the repository) into the newly created database to set up the required tables and initial data.
5. Update the database connection credentials in the PHP configuration file (usually found in a config.php or database.php file) to match your local MySQL username, password, and database name.

## How to Run the Application Locally
1. Clone this repository to your local machine.
2. Move the project folder into your local server's document root (e.g., the htdocs folder for XAMPP or the www folder for WAMP).
3. Complete the Database Setup Instructions outlined above.
4. Open your web browser and navigate to the project directory via localhost (e.g., http://localhost/Care-Box).
5. You can now interact with the Care-Box platform locally.
