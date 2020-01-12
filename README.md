WELCOME TO BINFO INTERNSHIPS WEBSITE README
-------------------------------------------

We are happy to let you know that all the required functionality is
working including bonus for specific time period messages!

#################################### 

DESCRIPTION:

This is a docker-env based web app that allows student: - View all BINFO
internships - Enroll/Unenroll from an internship (maximum 1) - Read/Send
messages in the internship page they are enrolled to - Easy navigate to
"my internship"

This app allows staff (lecturer): - View all BINFO internships -
Read/Send messages in the internship page they are related to
(local/academic supervisor)

This app allows admins: - View/Create/Disable/Modify users (only
non-admin users can be disabled or donwgraded) -
View/Create/Delete/Modify internships - Read/Send messages in all
internships pages
\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#

**BONUS IMPLEMENTATION** The webapp allows all users to read messages
from a specific time period

#################################### 

RUN:

In order to run the web-app: 1) Import the SQL dump webprogDB.sql to
your webprog DB 2) run docker-compose up 3) Navigate to
http://localhost/twitty/login.php

#################################### 

LOGIN:

The safe login method as required: sha256(x + password + d) is used
(using ajax) in the standard login page:
http://localhost/twitty/login.php
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ \*Incase you want to use an
unsafe login page you can use http://localhost/twitty/loginNotSafe.php
In that case only the username:password of blank:admin will work for you
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
http://localhost/twitty/login.php

You can login with (username:password) : (admin) root:admin vm:vm
(student) student1:student1

#################################### 

REGISTER:

Here you can find the registeration link (also available in the login
page as: Sign Up) http://localhost/twitty/register.php

Registeres users with password as plain-text (not safe) as required

#################################### 
