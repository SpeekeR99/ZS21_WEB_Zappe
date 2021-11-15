# Conference Web

## Project Description
This project implements a web application for managing conferences.
The application allows users to manage articles, reviews, user profiles, and other features related to conference organization.
The architecture is based on the MVC (Model-View-Controller) pattern, ensuring clarity and ease of extensibility.

## Functionality
- **User Management**:
  - Support for different roles: SuperAdmin, Admin, Reviewer, Author.
  - Login, registration, and user profile management.
- **Article Management**:
  - Uploading articles in PDF format.
  - Ability to edit and delete articles.
- **Article Reviews**:
  - Reviewers can rate and comment on uploaded articles.
- **Profile Pictures**:
  - Users can upload their own profile pictures.
- **Responsive Design**:
  - The website is optimized for various devices using Bootstrap.

## Technologies Used
1. **PHP**:
   - Main language for the backend of the application.
   - Handles form submissions, communicates with the database, and controls application logic.
2. **MySQL**:
   - Database system for storing data about users, articles, and reviews.
   - Protection against SQL Injection using prepared queries.
3. **HTML & Twig**:
   - HTML provides the structure of the pages.
   - Twig templates handle dynamic rendering of content on the user side.
4. **JavaScript & jQuery**:
   - Provide interactive elements such as the WYSIWYG editor (CKEditor) and asynchronous data loading using AJAX.
5. **Bootstrap & Font Awesome**:
   - Bootstrap for responsive design.
   - Font Awesome for icons on the website.
6. **Composer**:
   - Dependency management and downloading correct versions of libraries.
7. **Git**:
   - Version control system used for tracking changes in the project.

## Directory Structure
- **controllers/**: Contains PHP controller classes (e.g., `AController.abstract.php`), which handle user requests and communicate with the models.
- **models/**: Contains classes for working with the database (e.g., session management, user registration).
- **views/**: Contains `.twig` templates for rendering pages on the user side.
- **uploads/**: Directory for uploaded files:
  - `article/`: Stores articles in PDF format.
  - `avatar/`: Stores profile pictures in PNG, JPG, JPEG, and GIF formats.
- **composer/**: Contains the `composer.json` file for dependency management.

## Application Architecture
The application uses the MVC (Model-View-Controller) architecture:
1. **Models**: Communicate with the database and provide data to controllers.
2. **Controllers**: Process user requests, retrieve data from models, and pass it to views.
3. **Views**: Handle the rendering of pages based on data from controllers.

This architecture allows for easy maintenance and extension of the application.

## List of Default Users
| Login       | Password   |
|-------------|------------|
| SuperAdmin  | admin      |
| Admin       | admin      |
| Reviewer1   | reviewer   |
| Author1     | author     |

## How to Run
1. Clone the repository from GitHub using the following command:


	git clone https://github.com/SpeekeR99/ZS21_WEB_Zappe.git


2. Install Composer dependencies using the following command:


	composer install


3. Import the database schema from the `database.sql` file.
4. Configure the database connection in the `config.php` file.
5. Run the application on a local server or deploy it to a production server.