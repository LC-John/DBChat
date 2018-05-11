
use DBChat;

delete from CLIENT;
alter table CLIENT AUTO_INCREMENT=1;
delete from KNOWS;
alter table KNOWS AUTO_INCREMENT=1;
delete from FRIENDAPPLICATION;
alter table FRIENDAPPLICATION AUTO_INCREMENT=1;

call USER_SIGN_UP("Alice", '', @tmp_alice_cid);
call USER_SIGN_UP("Bob", '', @tmp_bob_cid);
call USER_SIGN_UP("Carl", '', @tmp_carl_cid);
call USER_SIGN_UP("Dave", '', @tmp_dave_cid);

call USER_FRIEND_APPLICATION(@tmp_alice_cid, '', @tmp_bob_cid, @tmp_faid, @tmp_granted);
call USER_ACCEPT_FRIEND_APPLICATION(@tmp_bob_cid, '', @tmp_faid, @tmp_granted);
call USER_FRIEND_APPLICATION(@tmp_alice_cid, '', @tmp_carl_cid, @tmp_faid, @tmp_granted);
call USER_ACCEPT_FRIEND_APPLICATION(@tmp_carl_cid, '', @tmp_faid, @tmp_granted);
call USER_FRIEND_APPLICATION(@tmp_carl_cid, '', @tmp_bob_cid, @tmp_faid, @tmp_granted);
call USER_ACCEPT_FRIEND_APPLICATION(@tmp_bob_cid, '', @tmp_faid, @tmp_granted);
call USER_FRIEND_APPLICATION(@tmp_alice_cid, '', @tmp_dave_cid, @tmp_faid, @tmp_granted);
call USER_REFUSE_FRIEND_APPLICATION(@tmp_dave_cid, '', @tmp_faid, @tmp_granted);

select * from CLIENT;
select * from KNOWS;
select * from FRIENDAPPLICATION;
