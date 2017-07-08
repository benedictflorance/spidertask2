Contains files for the first backend task of Spider Webdev

## **_Note: Screenshots can be found in the img directory of the repo_**
# Instructions to run the files
1. Install and setup WAMP Server
2. Create MySQL Account with username "adminprof" and password "phpiscool"
3. Create a database named "noticeboard"
4. Type the following MySQL commands:
```
CREATE TABLE `notes` (
  `id` int(11) PRIMARY AUTO_INCREMENT NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pendingnotes` (
  `id` int(11) PRIMARY AUTO_INCREMENT NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` int(11) PRIMARY AUTO_INCREMENT NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moderated` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
5. Create a folder called spidertask2 inside www directory
6. Put all php and css files from this repo into this spidertask2 directory
7. As one user should be made as the admin, make the first user registering as the admin using the command  `UPDATE users SET type='professor' WHERE id='1';` By default, the type is "student"


# Details about the task
**Software Stack used:** WAMP Stack (Windows+Apache Server+MySQL+PHP)

**Query used for creating admin:** CREATE USER 'adminprof'@'localhost' IDENTIFIED BY 'phpiscool'; GRANT ALL PRIVILEGES ON noticeboard.* TO 'adminprof'@'localhost' WITH GRANT OPTION; FLUSH PRIVILEGES;

**Hashing standard**: BCRYPT(password_hash and password_verify)

**Concepts Used**: Session and Auth

**Database Name**: notice board -> **Tables**: users, notes, pendingnotes

**Task details**: Normal+Bonus
 1. Login Page with proper form validation, styling and recaptcha(login.php)
 2. Registration Page with proper form validation, styling, recaptcha, to check duplicate user names.(register.php)
 3. Bulletin Board Page with notes and assignments being neatly displayed with serial number, type, content and issued date

**Admin Options(Professors)**
  1. Admin Panel(adminpanel.php)
      a) Notes/Assignment Panel (Can add or delete notes0
      b) Access Panel (Can change the access of a user to student/professor/class representative)
      c) Moderation Panel (Can mark a user as moderated, Moderated users'posts will be displayed under the pending section)
  2. Approval Panel(approve.php)
      a) Admin(Professor) can approve the pending notes sent by the moderated users.
      
**Class Rep Options**(crpanel.php)
  1. Class Representative Panel:(crpanel.php)
  Class Rep can add only new assignment which will be sent to the admin's pending notes panel for approval
  
 **Student Options**
 Students can only view notes on the bulletin board
 
 **Security**
  1. Users accessing any page will be shown access denied page and redirected for login.
  2. Students/CR will be shown access denied if they try to view admin and approval panel.
  3. Students cannot access CR panel. They will be shown access denied and redirected for login.
  4. Dashboard:
  a)Us
   
    


