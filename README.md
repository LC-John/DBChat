# DB Chat

Online chat based on relational database.

MySQL + Bootstrap implementation.

## Backend

There are 6 tables -- CLIENT, FRIENDAPPLICATION, KNOWS, SESSION, INSESSION, and MSG. Among all tables, CLIENT, SESSION and MSG are entities, and FRIENDAPPLICATION, KNOWS, INSESSION are relations.

The users are in the CLIENT table. Users are allowed to change password, rename themselves, purge themselves by their own. Users are also allowed to apply or delete friend with each other, with the help of the tables of FRIENDAPPLICATION and KNOWS.

The sessions, in which the users have their chatting, are in the SESSION table. The INSESSION table indicates that which user is in which session. There is a column in the SESSION table, called SVISIBLE, which indicates the type of the session. There are 2 kinds of sessions: 1) The group sessions are for group chatting. Users are allowed to join or be invited into the group sessions, or leave them. Special users, managers, are not allowed to leave the session, yet they have the ability to purge the session. Managers are the creators of the sessions by default. And the managers can transfer to others. 2) The private sessions are for private pair chatting. There are only two users in the private sessions. They are not allowed to leave the session, and others are not allowed to join or be invited into the private sessions. Both users in the private sessions are managers, which means they are allowed to purge the session. Private sessions are invisible to those who are not in them.

The messages sent by the users into the sessions are in the MSG table. The senders of the messages are allowed to withdraw their message by MID.

The conceptual data model designed in PowerDesigner is shown as below.

![image](https://github.com/LC-John/DBChat/blob/master/img/cdm.png)

The physical data model generated from the CDM is shown as below.

![image](https://github.com/LC-John/DBChat/blob/master/img/pdm.png)

The sql scripts are in the directory 'sql_script'. See README.md in the directory for details.

## Frontend

There are three major scenes in the frontend WEB server -- index.php, main.php, session_main.php.

The original home page is index.php. One may choose the sign up as a new user, or sign in as an existing user.

Once the users sign in, the home page becomes main.php. The users may leave and return to index.php. The users are allowed to rename, change password, apply friend to others, search for users and sessions, create new group/private sessions, etc.

The users come to session_main.php by clicking one of the sessions in which he / she is. The users are allowed to send messages, browse previous messages, and withdraw messages. The namagers are allowed to purge the session on one click.

The PHP scripts are in the directory 'front_end'. See README.md in the directory for details.

## Example

index.php

![image](https://github.com/LC-John/DBChat/blob/master/img/home.png)

main.php

![image](https://github.com/LC-John/DBChat/blob/master/img/user_home.png)

session_main.php

![image](https://github.com/LC-John/DBChat/blob/master/img/session.png)

manager dash board

![image](https://github.com/LC-John/DBChat/blob/master/img/manager.png)

## To be continued

Auto refreshing.
Image messages.
...