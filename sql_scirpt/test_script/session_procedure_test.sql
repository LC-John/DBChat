
use DBChat;

delete from SESSION;
alter table SESSION AUTO_INCREMENT=1;
delete from INSESSION;
alter table INSESSION AUTO_INCREMENT=1;

set @tmp_sess_pswd='hatter';
call SESSION_CREATE(@tmp_alice_cid, '', 'wonderland', @tmp_sess_pswd, @tmp_wonderland_sid, @tmp_granted);
call SESSION_INVITE(@tmp_alice_cid, '', @tmp_wonderland_sid, @tmp_bob_cid, @tmp_granted);
call SESSION_JOIN(@tmp_carl_cid, '', @tmp_wonderland_sid, @tmp_sess_pswd, @tmp_granted);
call SESSION_JOIN(@tmp_dave_cid, '', @tmp_wonderland_sid, @tmp_sess_pswd, @tmp_granted);

call SESSION_CREATE_FRIEND(@tmp_alice_cid, '', @tmp_bob_cid, @tmp_sid, @tmp_granted);

select * from SESSION;
select * from INSESSION;

/*
call SESSION_LEAVE(@tmp_alice_cid, '', @tmp_wonderland_sid, @tmp_granted);
call SESSION_LEAVE(@tmp_bob_cid, '', @tmp_wonderland_sid, @tmp_granted);
call SESSION_LEAVE(@tmp_carl_cid, '', @tmp_wonderland_sid, @tmp_granted);

select * from INSESSION;

call SESSION_CHANGE_MANAGER(@tmp_alice_cid, '', @tmp_wonderland_sid, @tmp_dave_cid, @tmp_granted);
call SESSION_LEAVE(@tmp_alice_cid, '', @tmp_wonderland_sid, @tmp_granted);
call SESSION_LEAVE(@tmp_alice_cid, '', @tmp_sid, @tmp_granted);

select * from INSESSION;

call SESSION_DESTROY(@tmp_dave_cid, '', @tmp_wonderland_sid, @tmp_granted);
call SESSION_DESTROY(@tmp_bob_cid, '', @tmp_sid, @tmp_granted);

select * from SESSION;
select * from INSESSION;
*/
