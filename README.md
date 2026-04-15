Project Name: UniFAST- TDP Scholarship Management System

## Setup Instructions

Follow these steps to set up the project locally from the GitHub repository.

### Prerequisites
- XAMPP (includes PHP, MySQL, and Apache)
- Visual Studio Code (recommended code editor)
- Composer
- Node.js(>= 14.x) and npm
- MySQL or another database supported by Laravel
- Git

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/ann1705/delossantos-sms.git
   cd delossantos-sms
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   - Copy the `.env.example` file to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your database credentials and other settings:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database_name
     DB_USERNAME=your_username
     DB_PASSWORD=your_password

     MAIL_MAILER=smtp
     MAIL_HOST=your_smtp_host
     MAIL_PORT=587
     MAIL_USERNAME=your_email@example.com
     MAIL_PASSWORD=your_email_password
     MAIL_ENCRYPTION=tls
     MAIL_FROM_ADDRESS=your_email@example.com
     MAIL_FROM_NAME="${APP_NAME}"
     ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Database Setup**
   - Create a database in your MySQL server
   - Run migrations:
     ```bash
     php artisan migrate
     ```
   - (Optional) Seed the database with sample data:
     ```bash
     php artisan db:seed
     ```

7. **Build Assets**
   ```bash
   npm run build
   ```

8. **Serve the Application**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`

### Additional Notes
- For password reset functionality, ensure your mail configuration is set up correctly (e.g., using Mailtrap for testing).
- If using XAMPP, make sure Apache and MySQL are running.


Feature List:

1. Authentication & User Management

o Sub-Feature: Administrator Authentication
▪ Action: Login (Secure admin access)
▪ Action: Logout
o Sub-Feature: User Profile
▪ Action: View Admin Profile
▪ Action: Update Admin Credentials

2. Scholarship Application Management (CRUD)

o Sub-Feature: Application Registry
▪ Action: Get/Display All Applications (Table View)
▪ Action: Filter Applications by Status (Pending/Approved/Rejected)
o Sub-Feature: Application Operations
▪ Action: Add New Application (Create)
▪ Action: View Applicant Details (Read/Modal View)
▪ Action: Edit/Update Application Status (Update)
▪ Action: Delete Application Record (Delete)

3. Program & Applicant Tracking

o Sub-Feature: Program Applied
▪ Action: Assign Applicants to Specific Programs (e.g., UniFAST- TDP)
o Sub-Feature: Applicant Records
▪ Action: Track Applicant Submission History

4. Reporting & Analytics

o Sub-Feature: Dashboard Overview
▪ Action: View Summary Statistics (Total Applicants, Pending, etc.)
o Sub-Feature: Document Generation
▪ Action: Download Application Form (PDF Export)

5. System Settings

o Sub-Feature: Sidebar Navigation
▪ Action: Navigate between Dashboard, Applicants, Reports, and
Users
