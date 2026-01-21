# MentorBridge - Online Mentoring Platform

## Project Overview

**MentorBridge** is a comprehensive web-based mentorship platform that connects students (mentees) with experienced mentors across various academic and professional fields. The platform facilitates knowledge sharing, skill development, and personalized learning through one-on-one mentoring sessions.

### Main Goals and Features

#### Core Objectives
- **Connect Learners with Experts**: Enable mentees to discover and book sessions with qualified mentors in diverse categories
- **Empower Mentors**: Provide mentors with tools to offer expertise, manage schedules, and earn income
- **Ensure Quality**: Admin oversight for mentor approval and platform management
- **Facilitate Learning**: Structured session booking, scheduling, and feedback system

#### Key Features

**For Mentees:**
- Browse mentors by categories (Programming, Mathematics, Business, Sciences, etc.)
- View detailed mentor profiles with ratings, reviews, and hourly rates
- Book sessions based on mentor availability
- Secure payment processing with transparent pricing
- Rate and review completed sessions
- Track upcoming and past sessions

**For Mentors:**
- Create comprehensive profiles showcasing skills and experience
- Set custom hourly rates
- Manage weekly availability with flexible time slots
- Accept/view session bookings
- Track earnings and session history
- Receive ratings and feedback

**For Administrators:**
- Approve or reject mentor applications
- Manage users (suspend, activate accounts)
- Oversee platform operations
- Monitor session activity
- View platform statistics

#### Business Model
- Mentees pay mentor's hourly rate + 20% platform fee
- Mentors receive their full hourly rate
- Platform retains 20% service fee

---

## Technologies Used

### Backend
- **PHP 7.4+** - Server-side scripting and business logic
- **MySQL/MariaDB** - Relational database management
- **MySQLi Extension** - Database connectivity with prepared statements

### Frontend
- **HTML5** - Semantic structure and content
- **CSS3** - Responsive styling with modern animations
- **JavaScript** - Client-side interactivity and form validation

### External Resources
- **Google Fonts (Inter)** - Modern typography

### Development Environment
- **XAMPP** - Local development stack (Apache + MySQL + PHP)
- **Apache Web Server** - HTTP server (port 80)
- **PhpMyAdmin** - Database administration interface

---

## Prerequisites

Before installing and running MentorBridge, ensure you have the following installed on your system:

### Required Software

1. **XAMPP (Version 7.4 or higher)**
   - **Download**: [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - **Includes**: Apache 2.4+, MySQL 5.7+, PHP 7.4+
   - **Supported OS**: Windows, macOS, Linux
   - **Required Components**: Apache and MySQL modules

2. **Web Browser (Modern)**
   - Google Chrome (recommended, version 90+)
   - Mozilla Firefox (version 88+)
   - Microsoft Edge (version 90+)
   - Safari (version 14+)

3. **Text Editor/IDE (Optional, for code review)**
   - Visual Studio Code (recommended)
   - Sublime Text
   - Notepad++
   - PhpStorm

### System Requirements

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| Operating System | Windows 7/macOS 10.13/Ubuntu 18.04 | Windows 10+/macOS 11+/Ubuntu 20.04+ |
| RAM | 2 GB | 4 GB or higher |
| Disk Space | 500 MB free | 1 GB or higher |
| PHP Version | 7.4 | 8.0 or 8.1 |
| MySQL Version | 5.7 | 8.0 or MariaDB 10.5+ |
| Network | Internet connection for Google Fonts | Stable broadband connection |

### PHP Extensions Required
The following PHP extensions must be enabled (included by default in XAMPP):
- `mysqli` - MySQL database connectivity
- `pdo_mysql` - PDO database support
- `session` - Session management
- `mbstring` - Multibyte string handling
- `fileinfo` - File upload handling

---

## Installation & Setup

Follow these detailed step-by-step instructions to set up MentorBridge on your local machine.

### Step 1: Install XAMPP

1. **Download XAMPP**
   - Visit [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Select the appropriate installer for your operating system
   - Download XAMPP version 7.4 or higher

2. **Run the Installer**
   - Execute the downloaded installer
   - **Windows**: Run as Administrator
   - **macOS/Linux**: Grant necessary permissions
   
3. **Installation Options**
   - Install Location: Use default (`C:\xampp` on Windows, `/Applications/XAMPP` on macOS)
   - Components: Ensure **Apache** and **MySQL** are selected
   - Complete the installation wizard

4. **Verify Installation**
   - Open XAMPP Control Panel
   - Start Apache (should show "Running" in green)
   - Start MySQL (should show "Running" in green)

### Step 2: Download/Clone the Project

**Option A: If Project is Already in Place**
```
Current location: C:\Users\Invader\Desktop\Programs\Xampp\htdocs\mentorbridge-project-php
```
No action needed - proceed to Step 3.

**Option B: Manual Setup (For New Installation)**

1. **Copy Project Files**
   - Copy the `mentorbridge-project-php` folder
   - Paste into XAMPP's `htdocs` directory
   - **Windows**: `C:\xampp\htdocs\`
   - **macOS**: `/Applications/XAMPP/htdocs/`
   - **Linux**: `/opt/lampp/htdocs/`

2. **Verify Project Location**
   ```
   Final path should be: [XAMPP_ROOT]/htdocs/mentorbridge-project-php/
   ```

3. **Check File Permissions (Linux/macOS only)**
   ```bash
   chmod -R 755 /opt/lampp/htdocs/mentorbridge-project-php
   chmod -R 777 /opt/lampp/htdocs/mentorbridge-project-php/uploads
   ```

### Step 3: Configure the Database

1. **Start XAMPP Services**
   - Open XAMPP Control Panel
   - Click **Start** next to Apache
   - Click **Start** next to MySQL
   - Verify both show "Running" status

2. **Access PhpMyAdmin**
   - Open your web browser
   - Navigate to: `http://localhost/phpmyadmin`
   - You should see the PhpMyAdmin interface

3. **Import Database Schema**
   
   **Method 1: Using PhpMyAdmin GUI (Recommended)**
   - In PhpMyAdmin, click on the **"Import"** tab in the top menu
   - Click **"Choose File"** button
   - Navigate to: `mentorbridge-project-php/database.sql`
   - Select the file and click **"Open"**
   - Scroll down and click **"Go"** button
   - Wait for success message: "Import has been successfully finished"

   **Method 2: Using SQL Tab**
   - In PhpMyAdmin, click on the **"SQL"** tab
   - Open `database.sql` in a text editor
   - Copy all the SQL code
   - Paste into the SQL query box
   - Click **"Go"** button

4. **Verify Database Creation**
   - In PhpMyAdmin left sidebar, you should see a database named: `mentorbridge`
   - Click on it to expand
   - Verify the following tables exist:
     - `users`
     - `mentor_profiles`
     - `mentee_profiles`
     - `categories`
     - `mentor_categories`
     - `sessions`
     - `mentor_availability`
     - `feedback`
     - `time_slots` (deprecated)

5. **Verify Sample Data**
   - Click on `users` table
   - Click **"Browse"** tab
   - You should see 3 sample users:
     - Admin: `admin@mentorbridge.com`
     - Mentor: `john.mentor@example.com`
     - Mentee: `jane.student@example.com`
   - Click on `categories` table
   - You should see 10 categories (Programming, School, University, etc.)

### Step 4: Configure Application Settings

1. **Database Configuration**
   - Open `config.php` in a text editor
   - Verify the database credentials (default values should work):
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'mentorbridge');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Empty password for XAMPP default
   ```

2. **Custom Configuration (If Needed)**
   - If you changed MySQL root password during XAMPP installation:
     - Update `DB_PASS` with your MySQL password
   - If using a different MySQL port:
     - Update `DB_HOST` to `localhost:PORT` (e.g., `localhost:3307`)

### Step 5: Create Uploads Directory

The `uploads/` directory should already exist. If not:

**Windows:**
```cmd
mkdir C:\xampp\htdocs\mentorbridge-project-php\uploads
```

**macOS/Linux:**
```bash
mkdir -p /opt/lampp/htdocs/mentorbridge-project-php/uploads
chmod 777 /opt/lampp/htdocs/mentorbridge-project-php/uploads
```

---

## How to Run the Project

### Starting the Application

1. **Ensure XAMPP Services are Running**
   ```
   ✓ Apache: Running (Port 80)
   ✓ MySQL: Running (Port 3306)
   ```

2. **Access the Application**
   - Open your web browser
   - Navigate to one of the following URLs:
     - `http://localhost/mentorbridge-project-php/`
     - `http://127.0.0.1/mentorbridge-project-php/`
   
3. **Landing Page**
   - You should see the MentorBridge landing page
   - Modern purple gradient design with animated background
   - Navigation options: Login, Register as Mentor, Register as Mentee

### Login Credentials

The database comes pre-loaded with sample accounts for testing:

| Role | Email | Password | Description |
|------|-------|----------|-------------|
| **Admin** | `admin@mentorbridge.com` | `admin123` | Full platform access, approve mentors |
| **Mentor** | `john.mentor@example.com` | `admin123` | Pre-approved mentor with sample profile |
| **Mentee** | `jane.student@example.com` | `admin123` | Mentee with sample session history |

### Navigation Flow

**For Mentees:**
1. Login → Mentee Dashboard
2. Browse mentors by category
3. View mentor profiles
4. Book sessions based on availability
5. View and manage sessions
6. Complete payments
7. Rate and review completed sessions

**For Mentors:**
1. Login → Mentor Dashboard (or Profile Setup if new)
2. Complete profile (bio, skills, hourly rate, categories)
3. Wait for admin approval
4. Manage availability (add/remove time slots)
5. View upcoming sessions
6. Track earnings

**For Admins:**
1. Login → Admin Dashboard
2. View pending mentor applications
3. Approve/reject mentors
4. Manage users (suspend/activate)
5. View platform statistics

### Platform-Specific Instructions

#### Windows
- Default installation works without modifications
- Apache runs on port 80 (ensure Skype or IIS are not using port 80)
- Access via: `http://localhost/mentorbridge-project-php/`

#### macOS
- XAMPP may require security permissions
- Allow Apache in System Preferences → Security & Privacy
- If port 80 is blocked, configure Apache to use port 8080:
  - Edit `httpd.conf`
  - Access via: `http://localhost:8080/mentorbridge-project-php/`

#### Linux
- XAMPP typically installed in `/opt/lampp/`
- Start services: `sudo /opt/lampp/lampp start`
- Grant permissions to uploads folder: `chmod 777 uploads/`
- Access via: `http://localhost/mentorbridge-project-php/`

---

## Testing the Project

### Automated Testing

**Note:** This project currently does not include automated unit tests (PHPUnit) or integration tests. Testing is performed manually following the test scenarios below.

**Assumption:** For production deployment, the following automated tests should be implemented:
- PHPUnit tests for database operations
- Selenium/Cypress for end-to-end UI testing
- API endpoint testing (if REST API is added)

### Manual Testing Instructions

Comprehensive manual testing scenarios to validate all functionality.

#### Test Scenario 1: User Registration & Authentication

**Test Case 1.1: Mentee Registration**
1. Navigate to `http://localhost/mentorbridge-project-php/`
2. Click **"Join as Mentee"** button
3. Fill in registration form:
   - Full Name: `Test Mentee`
   - Email: `testmentee@example.com`
   - Password: `password123`
   - Confirm Password: `password123`
   - Role: Mentee (should be pre-selected)
4. Click **"Register"** button

**Expected Results:**
- ✓ Redirect to Mentee Dashboard
- ✓ Welcome message displayed
- ✓ User email shown in header
- ✓ New record in `users` table with role='mentee'
- ✓ New record in `mentee_profiles` table

**Test Case 1.2: Mentor Registration**
1. Logout (if logged in)
2. Navigate to homepage
3. Click **"Become a Mentor"** button
4. Fill in registration form:
   - Full Name: `Test Mentor`
   - Email: `testmentor@example.com`
   - Password: `password123`
   - Confirm Password: `password123`
   - Role: Mentor (should be pre-selected)
5. Click **"Register"** button

**Expected Results:**
- ✓ Redirect to Mentor Profile Setup page
- ✓ Message: "Complete your profile to start mentoring"
- ✓ Status: "Pending Approval"
- ✓ New record in `mentor_profiles` with status='pending'

**Test Case 1.3: Login Validation**
1. Logout
2. Navigate to Login page
3. **Test Invalid Credentials:**
   - Email: `invalid@test.com`
   - Password: `wrongpassword`
   - Expected: Error message "Invalid email or password"
4. **Test Valid Credentials:**
   - Email: `admin@mentorbridge.com`
   - Password: `admin123`
   - Expected: Redirect to Admin Dashboard

**Test Case 1.4: Password Security**
1. Go to Registration page
2. Try password shorter than 6 characters: `12345`
3. Expected: Error "Password must be at least 6 characters"

#### Test Scenario 2: Mentor Profile Management

**Test Case 2.1: Complete Mentor Profile**
1. Login as mentor: `john.mentor@example.com` / `admin123`
2. Navigate to Mentor Profile page
3. Fill in/verify profile fields:
   - Full Name: `John Smith`
   - Bio: `Experienced developer...`
   - Skills: `JavaScript, Python, React`
   - Experience: `10 years as Senior Developer`
   - Hourly Rate: `75.00`
   - Categories: Select "Programming"
4. Upload profile image (optional)
5. Click **"Save Profile"** button

**Expected Results:**
- ✓ Success message: "Profile updated successfully"
- ✓ Data saved in `mentor_profiles` table
- ✓ Category association saved in `mentor_categories` table

**Test Case 2.2: Admin Approval Workflow**
1. Login as admin: `admin@mentorbridge.com` / `admin123`
2. Navigate to Admin Dashboard
3. Locate pending mentor in "Pending Approvals" section
4. Click **"Approve"** button next to mentor

**Expected Results:**
- ✓ Mentor status changes from 'pending' to 'approved'
- ✓ Mentor receives default availability slots (Mon-Fri, 9:00 AM)
- ✓ Mentor can now manage availability
- ✓ Mentor appears in public mentor listings

**Test Case 2.3: Mentor Rejection**
1. As admin, find a pending mentor
2. Click **"Reject"** button

**Expected Results:**
- ✓ Mentor status changes to 'rejected'
- ✓ Mentor cannot manage availability
- ✓ Mentor does not appear in public listings

#### Test Scenario 3: Mentor Availability Management

**Test Case 3.1: Add Availability Slots**
1. Login as approved mentor: `john.mentor@example.com` / `admin123`
2. Navigate to **"Manage Availability"**
3. Select day: `Monday`
4. Select time: `14:00` (2:00 PM)
5. Click **"Add Time Slot"** button

**Expected Results:**
- ✓ Success message: "Time slot added successfully"
- ✓ New slot appears in Monday's availability
- ✓ Record inserted in `mentor_availability` table
- ✓ Slot is marked as available (green indicator)

**Test Case 3.2: Slot Conflict Validation**
1. Try adding another slot on Monday at `14:30`
2. Expected: Error "Time slots must be at least 1 hour apart"

**Test Case 3.3: Toggle Availability**
1. Find an existing time slot
2. Click **"Toggle"** button to disable it
3. Expected: Slot grays out, `is_available` = 0
4. Click **"Toggle"** again to enable
5. Expected: Slot becomes green again, `is_available` = 1

**Test Case 3.4: Delete Time Slot**
1. Find an existing slot with no bookings
2. Click **"Delete"** button
3. Expected: Slot removed from list, deleted from database

#### Test Scenario 4: Session Booking & Payment

**Test Case 4.1: Browse Mentors**
1. Login as mentee: `jane.student@example.com` / `admin123`
2. From Mentee Dashboard, browse mentors
3. Click on category filter (e.g., "Programming")
4. Expected: Only mentors in that category are displayed

**Test Case 4.2: View Mentor Details**
1. Click on mentor card (e.g., "John Smith")
2. Verify displayed information:
   - Full name, bio, skills, experience
   - Hourly rate (e.g., $75.00)
   - Average rating (e.g., 4.8 ★)
   - Total reviews count
   - Available time slots organized by day

**Test Case 4.3: Book a Session**
1. On mentor detail page, scroll to "Available Time Slots"
2. Select a day (e.g., `Monday`)
3. Select a time slot (e.g., `09:00`)
4. Click **"Book Session"** button
5. Verify booking summary:
   - Mentor rate: $75.00
   - Platform fee (20%): $15.00
   - **Total amount: $90.00**
6. Click **"Confirm Booking"** button

**Expected Results:**
- ✓ Redirect to "My Sessions" page
- ✓ Success message: "Session scheduled successfully! Please complete payment"
- ✓ Session appears in "Pending" tab
- ✓ Session record created with status='pending', payment_status='pending'
- ✓ Time slot marked as unavailable in `mentor_availability`

**Test Case 4.4: Complete Payment**
1. In "My Sessions", find the pending session
2. Click **"Pay Now"** button
3. On payment page, verify session details
4. Click **"Confirm Payment"** button

**Expected Results:**
- ✓ Success message: "Payment successful"
- ✓ Session status changes to 'confirmed'
- ✓ Payment status changes to 'paid'
- ✓ Session moves to "Upcoming" tab

#### Test Scenario 5: Session Completion & Feedback

**Test Case 5.1: Complete a Session (Manual Database Update)**

Since sessions are scheduled for future dates, manually update the database to simulate completion:

1. Open PhpMyAdmin → `mentorbridge` → `sessions` table
2. Find a confirmed session
3. Click **"Edit"**
4. Change:
   - `status`: 'completed'
   - `scheduled_at`: Set to a past date (e.g., yesterday)
5. Click **"Go"** to save

**Test Case 5.2: Submit Feedback**
1. Login as mentee
2. Navigate to **"My Sessions"** → **"Completed"** tab
3. Find the completed session
4. Click **"Rate Mentor"** button
5. Select rating: 5 stars
6. Enter comment: `Excellent mentor! Very knowledgeable and patient.`
7. Click **"Submit Feedback"** button

**Expected Results:**
- ✓ Success message: "Thank you for your feedback"
- ✓ Feedback record created in `feedback` table
- ✓ Mentor's average rating recalculated (trigger fires)
- ✓ Mentor's total reviews count incremented
- ✓ Feedback button changes to "Rated" (disabled)

**Test Case 5.3: View Feedback on Mentor Profile**
1. Navigate to mentor's public profile
2. Expected: Updated rating visible (e.g., 4.8 → 4.9)
3. Expected: Review count increased
4. Expected: Recent feedback visible (if display is implemented)

#### Test Scenario 6: Admin Functions

**Test Case 6.1: User Management**
1. Login as admin: `admin@mentorbridge.com` / `admin123`
2. Navigate to Admin Dashboard → **"All Users"** section
3. Find a user
4. Click **"Suspend"** button

**Expected Results:**
- ✓ User status changes to 'suspended'
- ✓ User cannot login (error: "Your account has been suspended")

**Test Case 6.2: Activate Suspended User**
1. As admin, find suspended user
2. Click **"Activate"** button
3. Expected: Status changes to 'active', user can login

**Test Case 6.3: View Platform Statistics**
1. As admin, view dashboard metrics:
   - Total users count
   - Total mentors (approved/pending/rejected)
   - Total mentees
   - Total sessions (pending/confirmed/completed)
   - Platform revenue (20% of all paid sessions)

#### Test Scenario 7: Edge Cases & Error Handling

**Test Case 7.1: Duplicate Email Registration**
1. Try registering with existing email: `admin@mentorbridge.com`
2. Expected: Error "Email already registered"

**Test Case 7.2: Book Already Booked Slot**
1. As mentee, try booking a slot that's already booked
2. Expected: Slot should not be visible (filtered out)

**Test Case 7.3: Access Control**
1. Logout
2. Try accessing: `http://localhost/mentorbridge-project-php/admin-dashboard.php`
3. Expected: Redirect to login page
4. Login as mentee
5. Try accessing admin dashboard again
6. Expected: Redirect to mentee dashboard (role-based access control)

**Test Case 7.4: SQL Injection Prevention**
1. On login page, try SQL injection:
   - Email: `admin' OR '1'='1`
   - Password: `anything`
2. Expected: Error "Invalid email or password" (prepared statements prevent injection)

**Test Case 7.5: Empty Form Submission**
1. On any form, leave all fields empty
2. Click submit
3. Expected: Validation error messages displayed

### Expected Test Results Summary

| Test Area | Total Test Cases | Expected Pass Rate |
|-----------|------------------|-------------------|
| Authentication | 4 | 100% |
| Mentor Profile | 3 | 100% |
| Availability | 4 | 100% |
| Session Booking | 4 | 100% |
| Feedback | 3 | 100% |
| Admin Functions | 3 | 100% |
| Edge Cases | 5 | 100% |
| **TOTAL** | **26** | **100%** |

---

## Project Structure

```
mentorbridge-project-php/
│
├── config.php                  # Database configuration & helper functions
├── database.sql                # Complete database schema with sample data
├── index.php                   # Landing page (homepage)
├── login.php                   # Login page for all users
├── register.php                # Registration page (mentor/mentee)
├── logout.php                  # Logout handler
├── dashboard.php               # Main dashboard router (role-based redirect)
│
├── admin-dashboard.php         # Admin control panel
│   ├── View all users
│   ├── Approve/reject mentors
│   ├── Suspend/activate users
│   └── Platform statistics
│
├── mentor-dashboard.php        # Mentor home page
├── mentor-profile.php          # Mentor profile creation/editing
├── manage-availability.php     # Mentor availability management
│
├── mentee-dashboard.php        # Mentee home page (browse mentors)
├── mentor-detail.php           # Public mentor profile view
├── metnor-detail.php           # (Typo - same as mentor-detail.php)
├── book-session.php            # Session booking & payment
├── my-sessions.php             # View upcoming/past sessions
├── payment.php                 # Payment processing page
│
├── uploads/                    # Directory for user-uploaded files
│   └── (profile images, documents)
│
├── README.md                   # This file - project documentation
└── .git/                       # Git version control (optional)
```

### Key Files Explained

#### Configuration & Core
- **config.php**: Database connection settings, session management, authentication helpers (`isLoggedIn()`, `requireRole()`), and input sanitization functions

#### Database
- **database.sql**: Complete database schema including:
  - 8 tables (users, mentor_profiles, mentee_profiles, categories, sessions, etc.)
  - Indexes for performance optimization
  - Triggers for auto-updating ratings and creating default availability
  - Views for session overview and mentor statistics
  - Sample data for testing (3 users, 10 categories, 1 session)

#### Public Pages
- **index.php**: Landing page with modern UI, category showcase, and call-to-action buttons
- **login.php**: Authentication page with error handling and session creation
- **register.php**: Role-based registration with form validation

#### Mentor Pages
- **mentor-dashboard.php**: Mentor home showing sessions, earnings, and quick stats
- **mentor-profile.php**: Profile editor for bio, skills, hourly rate, and categories
- **manage-availability.php**: Weekly schedule manager with add/delete/toggle functionality

#### Mentee Pages
- **mentee-dashboard.php**: Browse mentors by category, search functionality
- **mentor-detail.php / metnor-detail.php**: Detailed mentor view with booking interface
- **book-session.php**: Session booking with date/time selection and price calculation
- **my-sessions.php**: Session history (upcoming, pending, completed)
- **payment.php**: Payment confirmation page

#### Admin Pages
- **admin-dashboard.php**: Centralized admin control panel for platform management

---

## Configuration

### Database Configuration

Edit `config.php` to customize database connection:

```php
// Database credentials
define('DB_HOST', 'localhost');      // MySQL host (default: localhost)
define('DB_NAME', 'mentorbridge');   // Database name
define('DB_USER', 'root');           // MySQL username (default: root)
define('DB_PASS', '');               // MySQL password (empty for XAMPP)
```

**Custom Port Configuration:**
If MySQL runs on a non-standard port (e.g., 3307):
```php
define('DB_HOST', 'localhost:3307');
```

### Session Configuration

Sessions are configured in `config.php` with `session_start()`. Default session settings:
- Session timeout: PHP default (24 minutes of inactivity)
- Session storage: Server filesystem
- Session cookie: `PHPSESSID`

**To modify session timeout**, add to `config.php`:
```php
ini_set('session.gc_maxlifetime', 3600);  // 1 hour in seconds
session_set_cookie_params(3600);           // 1 hour
```

### File Upload Configuration

**Upload Directory:** `uploads/` (must have write permissions)

**Supported File Types (Profile Images):**
- JPEG/JPG
- PNG
- GIF

**Maximum File Size:**
Default PHP settings apply (usually 2MB). To increase:

Edit `php.ini` (located in `C:\xampp\php\php.ini`):
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

Restart Apache after changes.

### Environment Variables

This project uses PHP constants instead of environment variables. For production deployment, consider using `.env` files with:

```env
DB_HOST=localhost
DB_NAME=mentorbridge
DB_USER=root
DB_PASS=your_password
APP_ENV=production
APP_DEBUG=false
```

And load with `vlucas/phpdotenv` library.

### Time Zone Configuration

Default: Server timezone (PHP default)

To set explicitly, add to `config.php`:
```php
date_default_timezone_set('America/New_York');  // Change as needed
```

---

## Common Issues & Troubleshooting

### Issue 1: "Database connection failed"

**Symptoms:**
- Error message on page load: "Database connection failed: Connection refused"
- Cannot access any page

**Solutions:**
1. **Verify MySQL is running:**
   - Open XAMPP Control Panel
   - Check MySQL status shows "Running"
   - If not, click "Start"

2. **Check database credentials in `config.php`:**
   ```php
   define('DB_USER', 'root');    // Default XAMPP username
   define('DB_PASS', '');        // Default XAMPP has no password
   ```

3. **Verify database exists:**
   - Open PhpMyAdmin: `http://localhost/phpmyadmin`
   - Check if `mentorbridge` database exists
   - If not, re-import `database.sql`

4. **Check MySQL port:**
   - Default port: 3306
   - If changed, update `config.php`: `define('DB_HOST', 'localhost:3307');`

### Issue 2: "Access forbidden" or Apache 403 Error

**Symptoms:**
- 403 Forbidden error when accessing project
- "You don't have permission to access this resource"

**Solutions:**
1. **Check Apache is running:**
   - XAMPP Control Panel → Apache should show "Running"

2. **Verify project path:**
   - Ensure project is in `htdocs` folder
   - Correct: `C:\xampp\htdocs\mentorbridge-project-php\`
   - Wrong: `C:\Users\Desktop\mentorbridge-project-php\`

3. **Check file permissions (Linux/macOS):**
   ```bash
   chmod -R 755 /opt/lampp/htdocs/mentorbridge-project-php
   ```

### Issue 3: Port 80 Already in Use

**Symptoms:**
- Apache fails to start
- Error: "Port 80 in use by another application"

**Common Conflicts:**
- Skype (Windows)
- IIS (Windows)
- Other web servers

**Solutions:**

**Option A: Stop conflicting application**
- Windows: Stop IIS or Skype
- Disable Skype's use of port 80/443 in Settings

**Option B: Change Apache port**
1. In XAMPP, click "Config" next to Apache → `httpd.conf`
2. Find line: `Listen 80`
3. Change to: `Listen 8080`
4. Save and restart Apache
5. Access via: `http://localhost:8080/mentorbridge-project-php/`

### Issue 4: Blank White Page

**Symptoms:**
- Page loads but shows nothing (blank white screen)
- No error messages visible

**Solutions:**
1. **Enable PHP error display:**
   
   Add to `config.php` (top of file):
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

2. **Check PHP error log:**
   - Location: `C:\xampp\apache\logs\error.log`
   - Look for recent PHP errors

3. **Verify PHP version:**
   - Create `info.php` with: `<?php phpinfo(); ?>`
   - Access: `http://localhost/mentorbridge-project-php/info.php`
   - Verify PHP version is 7.4+

### Issue 5: "Session already started" Warning

**Symptoms:**
- Warning: "session_start(): A session had already been started"

**Solution:**
- This is normal if `config.php` is included multiple times
- Modify `config.php` to add check:
  ```php
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  ```

### Issue 6: "Cannot modify header information" Error

**Symptoms:**
- Warning: "Cannot modify header information - headers already sent"
- Occurs during redirects

**Causes:**
- Whitespace before `<?php` tag
- Output (echo/print) before redirect

**Solutions:**
1. Remove any whitespace before `<?php` in files
2. Ensure no `echo` statements before `header()` calls
3. Use output buffering in `config.php`:
   ```php
   ob_start();  // Add at top of config.php
   ```

### Issue 7: Upload Directory Not Writable

**Symptoms:**
- Profile image upload fails
- Error: "Failed to move uploaded file"

**Solutions:**

**Windows:**
```cmd
# Open Command Prompt as Administrator
icacls "C:\xampp\htdocs\mentorbridge-project-php\uploads" /grant Everyone:F
```

**Linux/macOS:**
```bash
chmod 777 /opt/lampp/htdocs/mentorbridge-project-php/uploads
```

### Issue 8: Google Fonts Not Loading

**Symptoms:**
- Page uses default fonts instead of Inter
- Fonts look different than expected

**Solutions:**
1. Check internet connection (fonts load from Google CDN)
2. If offline, download fonts and host locally:
   - Download Inter font from Google Fonts
   - Place in `assets/fonts/` directory
   - Update CSS to use local fonts

### Issue 9: Login Redirects to Blank Page

**Symptoms:**
- After login, redirected to blank page or error

**Solution:**
1. Check if `dashboard.php` exists
2. Verify user role in database matches expected values ('mentor', 'mentee', 'admin')
3. Clear browser cache and cookies
4. Check session configuration in `php.ini`

### Issue 10: Database Import Fails

**Symptoms:**
- PhpMyAdmin shows error during import
- Error: "Unknown column" or "Table already exists"

**Solutions:**
1. **Drop existing database first:**
   - In PhpMyAdmin, select `mentorbridge` database
   - Click "Drop" tab → Confirm
   - Re-import `database.sql`

2. **Check SQL mode:**
   - In PhpMyAdmin, go to Variables tab
   - Search for `sql_mode`
   - If strict, may cause issues with default values

3. **Import in smaller chunks:**
   - Copy portions of `database.sql`
   - Execute in SQL tab separately

---

## Assumptions & Limitations

### Assumptions

1. **Development Environment:**
   - This project is designed for local development using XAMPP
   - Not configured for production deployment without modifications
   - Assumes default XAMPP configuration (root user, no password)

2. **User Behavior:**
   - Users will use modern web browsers with JavaScript enabled
   - Users have stable internet connection (for Google Fonts)
   - Mentees understand the 20% platform fee model
   - Mentors will set realistic hourly rates

3. **Data Validation:**
   - Assumes users will input reasonable data
   - Email addresses are valid (no email verification implemented)
   - Users will not attempt malicious SQL injection (mitigated with prepared statements)

4. **Session Scheduling:**
   - Sessions are 60 minutes duration by default
   - Time slots are booked one week in advance
   - Mentors are available for the full hour of booked slot

5. **Payment Processing:**
   - Payment system is simulated (no real payment gateway integration)
   - Payment confirmation is instant and manual
   - No refund processing implemented

### Current Limitations

#### 1. **Authentication & Security**
- ❌ No email verification after registration
- ❌ No password reset/recovery functionality
- ❌ No two-factor authentication (2FA)
- ❌ Password hashing uses bcrypt (good) but no advanced security features
- ❌ No CSRF token protection on forms
- ❌ Session hijacking protection not implemented

#### 2. **Payment System**
- ❌ No real payment gateway integration (Stripe, PayPal, etc.)
- ❌ Payment is simulated with button click
- ❌ No transaction history/invoices
- ❌ No refund mechanism
- ❌ No escrow system (mentors can't withdraw earnings)

#### 3. **Notification System**
- ❌ No email notifications for:
  - Registration confirmation
  - Session booking confirmation
  - Session reminders
  - Mentor approval/rejection
- ❌ No in-app notifications
- ❌ No SMS notifications

#### 4. **Search & Filtering**
- ❌ Limited search functionality (only category-based filtering)
- ❌ No keyword search for mentor skills
- ❌ No advanced filters (price range, rating, availability)
- ❌ No sorting options (by rating, price, reviews)

#### 5. **Session Management**
- ❌ No video conferencing integration (Zoom, Google Meet)
- ❌ No calendar integration (Google Calendar, iCal)
- ❌ No session rescheduling functionality
- ❌ No session cancellation with automatic refund
- ❌ Sessions cannot exceed or be less than 60 minutes

#### 6. **User Interface**
- ❌ Not fully responsive on all mobile devices
- ❌ No dark mode option
- ❌ No accessibility features (screen reader support, keyboard navigation)
- ❌ No internationalization (English only)

#### 7. **Data & Analytics**
- ❌ Limited admin analytics
- ❌ No mentor earnings dashboard
- ❌ No revenue reports
- ❌ No data export functionality (CSV, PDF)

#### 8. **File Upload**
- ❌ Profile images only (no document uploads)
- ❌ No image resizing/optimization
- ❌ No file type validation (security risk)
- ❌ No maximum file size enforcement in code

#### 9. **Testing**
- ❌ No automated unit tests
- ❌ No integration tests
- ❌ No end-to-end tests
- ❌ Only manual testing documented

#### 10. **Scalability**
- ❌ Not optimized for large user bases (10,000+ users)
- ❌ No caching layer (Redis, Memcached)
- ❌ No CDN for static assets
- ❌ Sessions stored in filesystem (not database)

### Known Issues

1. **Typo in filename:** `metnor-detail.php` should be `mentor-detail.php`
2. **Mixed session status:** Database trigger creates default availability but UI doesn't always reflect immediately
3. **Time zone handling:** Uses server timezone, may cause booking conflicts across time zones
4. **Concurrent bookings:** Rare race condition if two mentees book same slot simultaneously

### Not Covered

- Real-time chat between mentor and mentee
- Video/audio call functionality
- Mobile application (iOS/Android)
- API for third-party integrations
- Multi-language support
- Advanced reporting and analytics
- Automated mentor verification (background checks)
- Dispute resolution system
- Subscription plans for mentees or mentors

---

## How to Evaluate / Grade / Validate the Project

This section provides a comprehensive evaluation guide for instructors, testers, and evaluators.

### Evaluation Checklist

#### Phase 1: Setup & Configuration (15 points)

| Task | Points | Verification Steps |
|------|--------|-------------------|
| XAMPP installation successful | 3 | Apache and MySQL running in XAMPP |
| Database imported correctly | 4 | 8 tables exist, sample data loaded |
| Application accessible | 3 | Landing page loads at localhost |
| No critical errors on load | 3 | No PHP errors, database connected |
| Configuration correct | 2 | config.php has valid credentials |

**Validation Steps:**
1. Open XAMPP Control Panel → Verify Apache & MySQL are running
2. Access PhpMyAdmin → Verify `mentorbridge` database exists
3. Navigate to `http://localhost/mentorbridge-project-php/`
4. Verify homepage loads without errors

---

#### Phase 2: User Authentication (20 points)

| Functionality | Points | How to Verify |
|--------------|--------|---------------|
| User registration (mentee) | 5 | Register new mentee, verify in database |
| User registration (mentor) | 5 | Register new mentor, check status=pending |
| Login functionality | 4 | Login with sample accounts |
| Logout functionality | 2 | Logout, verify redirect to login |
| Role-based access control | 4 | Try accessing admin page as mentee |

**Validation Steps:**
1. **Register as Mentee:**
   - Click "Join as Mentee" → Fill form → Submit
   - ✓ Check: Redirected to dashboard
   - ✓ Check: Record in `users` and `mentee_profiles` tables

2. **Register as Mentor:**
   - Click "Become a Mentor" → Fill form → Submit
   - ✓ Check: Profile status = 'pending'
   - ✓ Check: Cannot manage availability yet

3. **Login Test:**
   - Use: `admin@mentorbridge.com` / `admin123`
   - ✓ Check: Redirected to admin dashboard

4. **Access Control:**
   - Login as mentee
   - Try: `http://localhost/mentorbridge-project-php/admin-dashboard.php`
   - ✓ Check: Redirected away (not authorized)

---

#### Phase 3: Mentor Management (20 points)

| Functionality | Points | How to Verify |
|--------------|--------|---------------|
| Mentor profile creation | 5 | Complete profile with bio, skills, rate |
| Admin approval workflow | 5 | Admin can approve/reject mentors |
| Availability management | 6 | Add/delete/toggle time slots |
| Category assignment | 4 | Mentors assigned to correct categories |

**Validation Steps:**
1. **Profile Creation:**
   - Login as: `john.mentor@example.com` / `admin123`
   - Navigate to Mentor Profile
   - ✓ Verify all fields editable: bio, skills, experience, rate
   - ✓ Update and save → Check database for changes

2. **Approval Process:**
   - Login as admin
   - Find pending mentor
   - Click "Approve"
   - ✓ Check: Status changes to 'approved' in database
   - ✓ Check: Mentor can now access availability management

3. **Availability Slots:**
   - As approved mentor, go to "Manage Availability"
   - Add slot: Monday, 14:00
   - ✓ Check: Slot appears in list
   - ✓ Check: Record in `mentor_availability` table
   - Toggle availability
   - ✓ Check: `is_available` changes in database
   - Delete slot
   - ✓ Check: Record removed

4. **Categories:**
   - Edit mentor profile
   - Select "Programming" and "Mathematics"
   - ✓ Check: `mentor_categories` table has 2 records

---

#### Phase 4: Session Booking (25 points)

| Functionality | Points | How to Verify |
|--------------|--------|---------------|
| Browse mentors | 4 | Filter by category, view mentor list |
| View mentor details | 4 | See full profile, ratings, availability |
| Book session | 8 | Select slot, create booking |
| Payment processing | 5 | Simulate payment, status updates |
| Session appears in history | 4 | View in "My Sessions" |

**Validation Steps:**
1. **Browse Functionality:**
   - Login as: `jane.student@example.com` / `admin123`
   - On Mentee Dashboard, click "Programming" category
   - ✓ Check: Only programming mentors displayed

2. **Mentor Profile View:**
   - Click on mentor card (John Smith)
   - ✓ Verify displays: bio, skills, rate, rating, availability slots

3. **Booking Process:**
   - Select available slot: Monday, 09:00
   - Click "Book Session"
   - ✓ Check calculation:
     - Mentor rate: $75.00
     - Platform fee (20%): $15.00
     - Total: $90.00
   - Confirm booking
   - ✓ Check: Record in `sessions` table with status='pending'

4. **Payment:**
   - In "My Sessions", find pending session
   - Click "Pay Now" → Confirm Payment
   - ✓ Check: `payment_status` = 'paid'
   - ✓ Check: `status` = 'confirmed'

5. **Session History:**
   - Navigate to "My Sessions"
   - ✓ Check: Session appears in "Upcoming" tab
   - ✓ Verify: Correct mentor, date, time, amount

---

#### Phase 5: Feedback & Rating (10 points)

| Functionality | Points | How to Verify |
|--------------|--------|---------------|
| Submit rating | 5 | Rate completed session (1-5 stars) |
| Rating calculation | 3 | Mentor's average rating updates |
| Feedback display | 2 | Feedback visible on mentor profile |

**Validation Steps:**
1. **Mark Session Complete (Database):**
   - PhpMyAdmin → `sessions` table
   - Edit a confirmed session
   - Set `status` = 'completed', `scheduled_at` = yesterday's date
   - Save

2. **Submit Feedback:**
   - Login as mentee
   - "My Sessions" → "Completed" tab
   - Click "Rate Mentor"
   - Select 5 stars
   - Comment: "Excellent mentor!"
   - Submit
   - ✓ Check: Record in `feedback` table

3. **Rating Update:**
   - Query `mentor_profiles` table
   - ✓ Verify: `average_rating` updated
   - ✓ Verify: `total_reviews` incremented
   - View mentor's public profile
   - ✓ Check: New rating displayed

---

#### Phase 6: Admin Functions (10 points)

| Functionality | Points | How to Verify |
|--------------|--------|---------------|
| View all users | 2 | List displays mentors, mentees |
| Approve/reject mentors | 4 | Change mentor status |
| Suspend/activate users | 3 | Toggle user account status |
| View statistics | 1 | Dashboard shows counts |

**Validation Steps:**
1. **User Management:**
   - Login as: `admin@mentorbridge.com` / `admin123`
   - Admin Dashboard → "All Users"
   - ✓ Verify: List shows all registered users

2. **Mentor Approval:**
   - Find pending mentor
   - Click "Approve"
   - ✓ Check: Mentor status = 'approved'
   - Click "Reject" on another
   - ✓ Check: Mentor status = 'rejected'

3. **Account Suspension:**
   - Find active user
   - Click "Suspend"
   - ✓ Check: `status` = 'suspended' in database
   - Logout and try logging in as suspended user
   - ✓ Check: Error "Your account has been suspended"
   - Login as admin again, click "Activate"
   - ✓ Check: User can login again

4. **Statistics:**
   - View admin dashboard
   - ✓ Verify counts displayed:
     - Total users
     - Total mentors (by status)
     - Total sessions
     - Platform revenue

---

### Grading Rubric Summary

| Category | Max Points | Focus Areas |
|----------|-----------|-------------|
| Setup & Configuration | 15 | Installation, database, no errors |
| Authentication | 20 | Register, login, access control |
| Mentor Management | 20 | Profile, approval, availability |
| Session Booking | 25 | Browse, book, payment flow |
| Feedback System | 10 | Rating, calculation, display |
| Admin Functions | 10 | User management, approvals |
| **TOTAL** | **100** | |

### Bonus Points (Optional, +10)

| Feature | Points | Criteria |
|---------|--------|----------|
| Code quality | 3 | Clean, commented, organized |
| UI/UX design | 3 | Modern, intuitive, responsive |
| Error handling | 2 | Graceful error messages |
| Security practices | 2 | Prepared statements, sanitization |

### Minimum Passing Criteria

To pass evaluation:
- ✓ Application runs without critical errors
- ✓ Database properly configured
- ✓ Users can register and login
- ✓ Mentees can book sessions
- ✓ Admin can manage users
- **Minimum score: 70/100**

### Critical Issues (Auto-Fail)

- ❌ Application doesn't run at all
- ❌ Database connection fails
- ❌ SQL injection vulnerabilities present (test basic injection)
- ❌ Severe security flaws (passwords stored in plaintext)

---

## Quick Start Summary

**For the impatient evaluator:**

```bash
# 1. Start XAMPP
Open XAMPP Control Panel → Start Apache & MySQL

# 2. Import Database
http://localhost/phpmyadmin → Import → database.sql

# 3. Access Application
http://localhost/mentorbridge-project-php/

# 4. Test Login
Admin: admin@mentorbridge.com / admin123
Mentor: john.mentor@example.com / admin123
Mentee: jane.student@example.com / admin123
```

**5-Minute Test:**
1. Login as mentee → Browse mentors → View profile → Book session → Pay
2. Login as admin → Approve pending mentor → View users
3. Login as mentor → Add availability slot → View sessions

---

## Support & Contact

For technical issues or questions about this project:

- **Email**: (Add your instructor's email or your email)
- **Course**: (Add course name/code)
- **Semester**: (Add semester)
- **University**: (Add university name)

---

## License

This project is developed for educational purposes as part of a university course assignment.

© 2026 MentorBridge Project. All rights reserved.

---

**Last Updated:** January 12, 2026  
**Version:** 1.0  
**Author:** (Add your name)
