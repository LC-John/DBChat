
use DBChat;

set @alice_pswd="1";
set @bob_pswd="2";
set @carl_pswd="3";
set @dave_pswd="4";

call USER_SIGN_UP("Alice", @alice_pswd, @alice_cid);
call USER_SIGN_UP("Bob", @bob_pswd, @bob_cid);
call USER_SIGN_UP("Carl", @carl_pswd, @carl_cid);
call USER_SIGN_UP("Dave", @dave_pswd, @dave_cid);

select * from CLIENT;
select * from KNOWS;

call USER_GET_FRIEND(@carl_cid, @carl_pswd, @tmp_granted);
call USER_GET_FRIEND(@dave_cid, @dave_pswd, @tmp_granted);

call USER_GET_NAME(@alice_cid, @alice_pswd, @alice_name, @tmp_granted);
select @alice_name, @tmp_granted;
call USER_GET_NAME(@bob_cid, @bob_pswd, @bob_name, @tmp_granted);
select @bob_name, @tmp_granted;
call USER_GET_NAME(@bob_cid, @carl_pswd, @carl_name, @tmp_granted);
select @bob_name, @tmp_granted;

call USER_FRIEND_APPLICATION(@alice_cid, @alice_pswd, @bob_cid, @bob_faid, @tmp_granted);
select @bob_faid, @tmp_granted;
call USER_FRIEND_APPLICATION(@bob_cid, @bob_pswd, @alice_cid, @tmp_faid, @tmp_granted);
select @tmp_faid, @tmp_granted;
call USER_FRIEND_APPLICATION(@bob_cid, @bob_pswd, @bob_cid, @tmp_faid, @tmp_granted);
select @tmp_faid, @tmp_granted;
call USER_FRIEND_APPLICATION(@bob_cid, @bob_pswd, @carl_cid, @carl_faid, @tmp_granted);
select @carl_faid, @tmp_granted;

select * from FRIENDAPPLICATION;
select * from KNOWS;

call USER_GET_FRIEND_APPLICATION(@alice_cid, @alice_pswd, @tmp_granted);
call USER_GET_FRIEND_APPLICATION(@bob_cid, @bob_pswd, @tmp_granted);
call USER_GET_FRIEND_APPLICATION(@carl_cid, @carl_pswd, @tmp_granted);
call USER_GET_FRIEND_APPLICATION(@dave_cid, @dave_pswd, @tmp_granted);

call USER_ACCEPT_FRIEND_APPLICATION(@bob_cid, @bob_pswd, @bob_faid, @tmp_granted);
call USER_REFUSE_FRIEND_APPLICATION(@carl_cid, @carl_pswd, @carl_faid, @tmp_granted);

select * from FRIENDAPPLICATION;
select * from KNOWS;
