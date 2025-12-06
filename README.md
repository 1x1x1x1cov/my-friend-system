# My Friend System

A PHP/MySQL social networking application that allows users to register, log in, add friends, and manage their friend connections. This project was developed as part of the COS30020 Advanced Web Development assignment.

## Table of Contents

- [Features Overview](#features-overview)
- [Technical Requirements](#technical-requirements)
- [Database Setup](#database-setup)
- [File Structure](#file-structure)
- [Installation & Setup](#installation--setup)
- [Usage Guide](#usage-guide)
- [Page Descriptions](#page-descriptions)
- [Extra Features](#extra-features)
- [Important Notes](#important-notes)
- [Testing Credentials](#testing-credentials)

## Features Overview

The My Friend System provides the following core functionality:

- **User Registration**: New users can sign up with email, profile name, and password
- **User Authentication**: Secure login system with session management
- **Friend Management**:
  - View your current friends list
  - Add new friends from registered users
  - Remove existing friends (unfriend)
  - Automatic bidirectional friendship relationships
- **Pagination**: Browse available users with 10 users per page 
- **Mutual Friends**: See how many mutual friends you share with potential connections
- **Dynamic Navigation**: Context-aware navigation bar that changes based on login status
- **Auto-Population**: Database automatically creates and populates sample data on first load

## Technical Requirements

### Server Requirements

- **PHP**: Version 7.0 or higher
- **MySQL/MariaDB**: Version 5.6 or higher
- **Web Server**: Apache (recommended) or compatible server
- **PHP Extensions**: MySQLi extension enabled

### Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- JavaScript not required (pure server-side application)

## Database Setup

### Database Configuration

You will need to update `settings.php` with your own database credentials.

### Database Tables

The system uses two main tables:

#### 1. `friends` Table

Stores user account information and friend counts.

```sql
CREATE TABLE friends (
    friend_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    friend_email VARCHAR(40) NOT NULL,
    password VARCHAR(15) NOT NULL,
    profile_name VARCHAR(35) NOT NULL,
    date_started DATE NOT NULL,
    num_of_friends INT UNSIGNED DEFAULT 0
);
```

**Columns:**
- `friend_id`: Unique user identifier (auto-increment)
- `friend_email`: User's email address (unique)
- `password`: User's password (plain text - see Important Notes)
- `profile_name`: Display name (letters only)
- `date_started`: Account creation date
- `num_of_friends`: Current friend count (auto-updated)

#### 2. `myfriends` Table

Stores friendship relationships between users.

```sql
CREATE TABLE myfriends (
    friend_id1 INT NOT NULL,
    friend_id2 INT NOT NULL
);
```

**Columns:**
- `friend_id1`: First user's ID
- `friend_id2`: Second user's ID

**Note**: Friendships are bidirectional. When User A adds User B as a friend, two records are created:
- (A, B) - A is friends with B
- (B, A) - B is friends with A

### Database Initialization

**The database tables are automatically created and populated when you first access `index.php`.**

The system will:
1. Create the `friends` and `myfriends` tables if they don't exist
2. Populate `friends` with 25 sample users (only if tables are empty)
3. Populate `myfriends` with 80+ sample friendship relationships
4. Update friend counts automatically

**Sample Data Includes:**
- 25 pre-registered users with varied friendship networks
- 80+ friendship connections demonstrating mutual friends
- Diverse data to properly showcase pagination and mutual friend features

## File Structure

```
PRIVATE-PHP-FRIENDS_SYSTEM/
│
├── index.php           # Home page - creates tables, displays welcome
├── signup.php          # User registration page
├── login.php           # User authentication page
├── logout.php          # Session destruction and logout
├── friendlist.php      # Display current user's friends
├── friendadd.php       # Add new friends (with pagination)
├── about.php           # Assignment report and information
├── navigation.php      # Reusable navigation bar component
├── settings.php        # Database configuration and connection functions
├── style.css           # Stylesheet for entire application
```

### File Dependencies

- All PHP pages require `settings.php` for database connections
- Most pages use `navigation.php` for consistent navigation
- All pages link to `style.css` for styling

## Installation & Setup

### Step 1: Download/Clone Repository

```bash
git clone https://github.com/1x1x1x1cov/my-friend-system
cd PRIVATE-PHP-FRIENDS_SYSTEM
```

### Step 2: Configure Database

1. Open `settings.php` in a text editor
2. Update the database credentials:

```php
define('DB_HOST', 'your-database-host');
define('DB_USER', 'your-database-username');
define('DB_PASS', 'your-database-password');
define('DB_NAME', 'your-database-name');
```

### Step 3: Deploy to Server

#### Option A: Mercury Server 

1. Connect to Mercury server via SFTP/SSH
2. Upload all files to your public web directory
3. Ensure file permissions are correct:
   ```bash
   chmod 644 *.php *.css
   chmod 755 .
   ```

#### Option B: Local Development (XAMPP/WAMP/MAMP)

1. Copy files to your web server's document root:
   - XAMPP: `htdocs/friendsystem/`
   - WAMP: `www/friendsystem/`
   - MAMP: `htdocs/friendsystem/`
2. Start Apache and MySQL services
3. Create a database using phpMyAdmin

### Step 4: First-Time Setup

1. Navigate to `index.php` in your web browser:
   ```
   http://your-server/path-to-app/index.php
   ```
2. The system will automatically:
   - Create the database tables
   - Populate sample data
   - Display a success message

### Step 5: Verify Installation

- You should see the home page with student information
- Check that the navigation bar displays correctly
- Try logging in with one of the sample accounts (see Testing Credentials)

## Usage Guide

### How to Sign Up

1. Navigate to the home page (`index.php`)
2. Click the "Sign Up" button or link
3. Fill in the registration form:
   - **Email**: Must be a valid email format
   - **Profile Name**: Letters only (spaces allowed)
   - **Password**: Letters and numbers only
   - **Confirm Password**: Must match password
4. Click "Register"
5. Upon successful registration, you'll be automatically logged in and redirected to the Add Friends page

**Validation Rules:**
- Email must be unique (not already registered)
- Profile name must contain only letters
- Password must contain only letters and numbers
- Passwords must match

### How to Log In

1. Navigate to the login page (`login.php`)
2. Enter your registered email and password
3. Click "Log in"
4. Upon successful login, you'll be redirected to your Friend List page

**Note**: If already logged in, accessing login page will redirect you to Friend List.

### How to Add Friends

1. After logging in, click "Add Friends" in the navigation bar
2. Browse the list of available users (users who are not already your friends)
3. View mutual friend counts displayed under each user's name
4. Click "Add as friend" button next to the user you want to add
5. The page will refresh, and the user will be moved to your friends list
6. Use "Previous" and "Next" buttons to navigate between pages (10 users per page)

**Features:**
- Mutual friends display: "(X mutual friends)" shows shared connections
- Pagination: Navigate through multiple pages of users
- Automatic bidirectional friendship (they become your friend automatically)
- Friend count updates immediately

### How to Remove Friends

1. Navigate to "Friend List" page (`friendlist.php`)
2. View your current friends list
3. Click the "Unfriend" button next to the friend you want to remove
4. The friendship will be removed immediately (bidirectional)
5. Your friend count will update automatically

### Navigation Between Pages

**When Not Logged In:**
- Home
- Friend List (redirects to login)
- Add Friends (redirects to login)
- About
- Log In
- Sign Up

**When Logged In:**
- Friend List - View your current friends
- Add Friends - Browse and add new friends
- About - View assignment information
- Hello, [Your Name] - Welcome message
- Log Out - End your session

## Page Descriptions

### `index.php` - Home Page
- **Purpose**: Landing page and database initialization
- **Features**:
  - Displays student information and declaration
  - Creates database tables on first load
  - Populates sample data if tables are empty
  - Provides navigation to Sign Up and Log In
- **Access**: Public (no login required)

### `signup.php` - Registration Page
- **Purpose**: New user account creation
- **Features**:
  - Email, profile name, and password input
  - Server-side validation (email format, uniqueness, password matching)
  - Automatic login after successful registration
  - Redirects to Add Friends page after signup
- **Access**: Public (redirects if already logged in)

### `login.php` - Authentication Page
- **Purpose**: User login and session creation
- **Features**:
  - Email and password authentication
  - Session management
  - Redirects to Friend List after successful login
  - Error messaging for invalid credentials
- **Access**: Public (redirects if already logged in)

### `friendlist.php` - Friend List Page
- **Purpose**: Display current user's friends
- **Features**:
  - Shows all friends alphabetically by name
  - Displays total friend count
  - Unfriend functionality with immediate updates
  - Links to Add Friends page
- **Access**: Requires login (redirects to login page if not authenticated)

### `friendadd.php` - Add Friends Page
- **Purpose**: Browse and add new friends
- **Features**:
  - Lists available users (excludes self and existing friends)
  - Displays mutual friend counts for each user
  - Pagination (10 users per page) with Previous/Next navigation
  - Add friend functionality with automatic bidirectional relationship
  - Maintains pagination state after adding friends
- **Access**: Requires login
- **Extra Features**: Task 8 (Pagination), Task 9 (Mutual Friends)

### `about.php` - About Page
- **Purpose**: Assignment report and documentation
- **Features**:
  - Assignment completion status
  - Special features description
  - Challenges encountered
  - Future improvements
  - Testing instructions for markers
  - Sample login credentials
  - Discussion board contribution screenshots
- **Access**: Public

### `logout.php` - Logout Handler
- **Purpose**: Session termination
- **Features**:
  - Clears all session variables
  - Destroys session
  - Redirects to home page
- **Access**: Public

### `navigation.php` - Navigation Component
- **Purpose**: Reusable navigation bar
- **Features**:
  - Dynamic navigation based on login status
  - Active page highlighting
  - Displays logged-in user's name
  - Responsive design
- **Access**: Included by all pages

### `settings.php` - Database Configuration
- **Purpose**: Centralized database connection management
- **Features**:
  - Database connection settings
  - `getDBConnection()` function
  - `closeDBConnection()` function
  - Error handling for connection failures
- **Access**: Required by all pages with database access

### `style.css` - Stylesheet
- **Purpose**: Visual styling for entire application
- **Features**:
  - Consistent color scheme and typography
  - Responsive navigation bar
  - Form styling with focus states
  - Button variants (primary, secondary, success, danger)
  - Friend list and pagination styling
  - Error and success message styling

## Extra Features

### Task 8: Pagination (Extra Challenge)

**Implementation**: `friendadd.php`

- Displays 10 users per page in the Add Friends list
- Previous/Next navigation buttons
- Page number indicator (e.g., "Page 2 of 3")
- Disabled button states when at first/last page
- Maintains pagination state when adding friends
- Automatically populated with 25 sample users to demonstrate pagination

**Usage:**
```
- Navigate to Add Friends page after logging in
- Use "Previous" and "Next" buttons to browse pages
- Page state is preserved in URL parameter (?page=N)
```

### Task 9: Mutual Friends (Extra Challenge)

**Implementation**: `friendadd.php`

- Calculates and displays mutual friend counts for each potential friend
- Shows "(X mutual friends)" under user names
- Uses SQL INNER JOIN to efficiently find shared connections
- Only displays count if mutual friends exist (> 0)
- Populated with 80+ sample friendships to demonstrate diverse networks

**Usage:**
```
- View Add Friends page
- See mutual friend counts displayed as: "Oliver Davis (2 mutual friends)"
- Helps users decide which friends to add based on shared connections
```

**SQL Query Used:**
```sql
SELECT COUNT(*) as mutual_count
FROM myfriends mf1
INNER JOIN myfriends mf2 ON mf1.friend_id2 = mf2.friend_id2
WHERE mf1.friend_id1 = [current_user_id]
AND mf2.friend_id1 = [potential_friend_id]
```

## Important Notes

### Relative Paths
All file references use relative paths (not absolute) for portability:
```php
require_once 'settings.php';  // ✓ Correct
<link rel="stylesheet" href="style.css">  // ✓ Correct
```

### Mercury Server Compatibility
- Designed to work on Swinburne's Mercury server
- Uses MariaDB connection (feenix-mariadb.swin.edu.au)
- All paths are server-agnostic

### Session Management
- Sessions are started on all pages that require user identification
- Session variables used:
  - `$_SESSION['logged_in']` - Boolean login status
  - `$_SESSION['friend_id']` - User's database ID
  - `$_SESSION['friend_email']` - User's email
  - `$_SESSION['profile_name']` - User's display name
- Sessions are destroyed completely on logout

### Security Considerations

**Current Implementation:**
- Passwords are stored in **plain text** (NOT recommended for production)
- Basic SQL injection protection via `mysqli_real_escape_string()`
- XSS prevention via `htmlspecialchars()` for output

**Recommendations for Production:**
- Use password hashing (`password_hash()` and `password_verify()`)
- Implement prepared statements for SQL queries
- Add CSRF protection for forms
- Use HTTPS for secure transmission
- Implement rate limiting for login attempts

### Database Auto-Population

**First Load Behavior:**
- `index.php` checks if tables exist
- Creates `friends` and `myfriends` tables if missing
- Populates 25 sample users if `friends` table is empty
- Populates 80+ friendships if `myfriends` table is empty
- Updates friend counts automatically

**Subsequent Loads:**
- Tables are checked but not recreated
- Existing data is preserved
- New users can register normally

### Validation Rules

**Email:**
- Required field
- Must be valid email format (uses PHP `FILTER_VALIDATE_EMAIL`)
- Must be unique (checked against database)

**Profile Name:**
- Required field
- Must contain only letters (a-z, A-Z) and spaces
- Pattern: `/^[a-zA-Z ]+$/`

**Password:**
- Required field
- Must contain only letters and numbers
- Pattern: `/^[a-zA-Z0-9]+$/`
- Must match confirmation password

## Testing Credentials

The system is pre-populated with sample accounts for testing. You can log in with any of these:

### Sample Users

| Email | Password | Friend Count | Notes |
|-------|----------|--------------|-------|
| emma.wilson@email.com | pass123 | 6 | Good for testing mutual friends |
| james.brown@email.com | pass456 | 7 | Shares 4 mutual friends with Emma |
| sophia.taylor@email.com | pass789 | 6 | Good for testing friend list |
| oliver.davis@email.com | pass101 | 5 | - |
| ava.martinez@email.com | pass202 | 5 | - |

### Testing Scenarios

**Test Pagination:**
1. Log in with any account above
2. Navigate to "Add Friends"
3. Scroll to bottom to see pagination controls
4. Click "Next" to view additional users

**Test Mutual Friends:**
1. Log in as `emma.wilson@email.com` (pass123)
2. Navigate to "Add Friends"
3. Observe mutual friend counts displayed under names
4. Users with shared connections show "(X mutual friends)"

**Test Add/Remove Friends:**
1. Log in with any account
2. Add a friend from "Add Friends" page
3. Verify friend count increases
4. Go to "Friend List" and unfriend them
5. Verify friend count decreases

**Test Registration:**
1. Click "Sign Up" from home page
2. Create a new account with unique email
3. Verify automatic login and redirect to Add Friends

## Author

**Name**: Minh Hieu Ong
**Email**: ongminhhieu12@gmail.com

---

**Last Updated**: December 2025
**Assignment**: COS30020 Assignment 2 - My Friend System
