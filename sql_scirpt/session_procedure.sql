
use DBChat;

/*==============================================================*/
/* Create-pair-chatter-session procedure                        */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- CID of another user.                                   */
/* OUT -> Authorization granted (correct password) or not.      */
/*     -> If granted, sid of this session.                      */
/*==============================================================*/
drop procedure if exists SESSION_CREATE_FRIEND;
delimiter $$
create procedure SESSION_CREATE_FRIEND(IN user_cid int, IN user_pswd char(32), IN other_cid int, OUT sess_sid int, OUT granted int)
	begin
		declare name1 char(32);
		declare name2 char(32);
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			set granted=(select count(*) from KNOWS
				where KNOWS.CID=user_cid and KNOWS.CLI_CID=other_cid);
			if granted>0 then
				set name1=(select CNAME from CLIENT where CID=user_cid);
				set name2=(select CNAME from CLIENT where CID=other_cid);
				alter table SESSION add column __TMP_FLAG_COLUMN__ boolean not null default FALSE;
				insert into SESSION(CID, SNAME, SPASSWORD, SVISIBLE, __TMP_FLAG_COLUMN__)
					values (user_cid, CONCAT(name1," and ",name2,"'s session"), '', FALSE, TRUE);
				set sess_sid=(select SESSION.SID from SESSION where SESSION.__TMP_FLAG_COLUMN__=TRUE);
				insert into INSESSION(SID, CID, SPASSWORD) values
					(sess_sid, user_cid, ''), (sess_sid, other_cid, '');
				alter table SESSION drop column __TMP_FLAG_COLUMN__;
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
			end if;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Create-multi-chatter-session procedure                       */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- Name of this session.                                  */
/*    <- Password of this session.                              */
/* OUT -> Authorization granted (correct password) or not.      */
/*     -> If granted, sid of this session.                      */
/*==============================================================*/
drop procedure if exists SESSION_CREATE;
delimiter $$
create procedure SESSION_CREATE(IN user_cid int, IN user_pswd char(32), IN sess_name char(32), IN sess_pswd char(32), OUT sess_sid int, OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			alter table SESSION add column __TMP_FLAG_COLUMN__ boolean not null default FALSE;
			insert into SESSION(CID, SNAME, SPASSWORD, SVISIBLE, __TMP_FLAG_COLUMN__)
				values (user_cid, sess_name, sess_pswd, TRUE, TRUE);
			set sess_sid=(select SESSION.SID from SESSION where SESSION.__TMP_FLAG_COLUMN__=TRUE);
			insert into INSESSION(SID, CID, SPASSWORD) values (sess_sid, user_cid, sess_pswd);
			alter table SESSION drop column __TMP_FLAG_COLUMN__;
			update CLIENT set CACTIVE=CURRENT_TIMESTAMP
				where CID=user_cid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Search-for-session-by-name procedure                         */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- Name of a session.                                     */
/* OUT -> Authorization granted (correct password) or not.      */
/*     -> If granted, sid of this session.                      */
/*==============================================================*/
drop procedure if exists SESSION_SEARCH;
delimiter $$
create procedure SESSION_SEARCH(IN user_cid int, IN user_pswd char(32), IN sess_name char(32), OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			select s.SID, s.SNAME, s.CID, c.CNAME from SESSION as s, CLIENT as c
				where s.SNAME=sess_name and c.CID=s.CID and s.SVISIBLE=TRUE;
			update CLIENT set CACTIVE=CURRENT_TIMESTAMP
				where CID=user_cid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Join-session procedure                                       */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- SID of this session.                                   */
/*    <- Password of this session.                              */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists SESSION_JOIN;
delimiter $$
create procedure SESSION_JOIN(IN user_cid int, IN user_pswd char(32), IN sess_sid int, IN sess_pswd char(32), OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			set granted=(select count(SID) from SESSION
				where SID=sess_sid and SPASSWORD=sess_pswd and SVISIBLE=TRUE);
			if granted>0 then
				insert ignore into INSESSION(SID, CID, SPASSWORD) values (sess_sid, user_cid, sess_pswd);
				update SESSION set SACTIVE=CURRENT_TIMESTAMP
					where SID=sess_sid;
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
			end if;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Invite-friend procedure                                      */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- SID of this session.                                   */
/*    <- CID of the invited friend                              */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists SESSION_INVITE;
delimiter $$
create procedure SESSION_INVITE(IN user_cid int, IN user_pswd char(32), IN sess_sid int, IN other_cid int, OUT granted int)
	begin
		declare __tmp_password char(32) default "";
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(*) from CLIENT
			where CLIENT.CID=user_cid and CLIENT.CPASSWORD=user_pswd);
		if granted>0 then
			set granted=(select count(SESSION.SID) from SESSION, INSESSION
				where SESSION.SVISIBLE=TRUE and SESSION.SID=sess_sid and INSESSION.SID=sess_sid
					and INSESSION.CID=user_cid and SESSION.SPASSWORD=INSESSION.SPASSWORD);
			if granted>0 then
				set granted=(select count(*) from KNOWS
					where KNOWS.CID=user_cid and KNOWS.CLI_CID=other_cid);
				if granted>0 then
					set __tmp_password=(select INSESSION.SPASSWORD from INSESSION
						where INSESSION.SID=sess_sid and INSESSION.CID=user_cid);
					insert ignore into INSESSION(SID, CID, SPASSWORD) values (sess_sid, other_cid, __tmp_password);
					update SESSION set SACTIVE=CURRENT_TIMESTAMP
						where SID=sess_sid;
					update CLIENT set CACTIVE=CURRENT_TIMESTAMP
						where CID=user_cid;
				end if;
			end if;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Get-insession-chatters procedure                             */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- SID of this session.                                   */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists SESSION_GET_OTHERS_INSESSION;
delimiter $$
create procedure SESSION_GET_OTHERS_INSESSION(IN user_cid int, IN user_pswd char(32), IN sess_sid int, OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			set granted=(select count(*) from INSESSION
				where SID=sess_sid and CID=user_cid);
			if granted>0 then
				select c.CID, c.CNAME, s.CID=c.CID as MANAGER from CLIENT as c, INSESSION as i, SESSION as s
					where i.SID=sess_sid and i.CID=c.CID and s.SID=sess_sid;
				update SESSION set SACTIVE=CURRENT_TIMESTAMP
					where SID=sess_sid;
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
			end if;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Get-my-session procedure                                     */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- SID of this session.                                   */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists SESSION_GET_MY_SESSION;
delimiter $$
create procedure SESSION_GET_MY_SESSION(IN user_cid int, IN user_pswd char(32), OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			select s.SID, s.SNAME, (s.CID=user_cid or s.SVISIBLE=FALSE) as MANAGER from SESSION as s, INSESSION as i
				where i.CID=user_cid and i.SID=s.SID;
			update CLIENT set CACTIVE=CURRENT_TIMESTAMP
				where CID=user_cid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Change-manager procedure                                     */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- SID of this session.                                   */
/*    <- CID of the new manager.                                */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists SESSION_CHANGE_MANAGER;
delimiter $$
create procedure SESSION_CHANGE_MANAGER(IN user_cid int, IN user_pswd char(32), IN sess_sid int, IN other_cid int, OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			set granted=(select count(SESSION.SID) from SESSION, INSESSION
				where SESSION.SID=sess_sid and SESSION.CID=user_cid and INSESSION.SID=sess_sid
					and INSESSION.CID=user_cid and SESSION.SPASSWORD=INSESSION.SPASSWORD);
			if granted>0 then
				update SESSION set CID=other_cid, SACTIVE=CURRENT_TIMESTAMP
					where SID=sess_sid;
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
			end if;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Destroy-session procedure                                    */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- SID of this session.                                   */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists SESSION_DESTROY;
delimiter $$
create procedure SESSION_DESTROY(IN user_cid int, IN user_pswd char(32), IN sess_sid int, OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			set granted=(select count(SESSION.SID) from SESSION, INSESSION
				where (SESSION.SID=sess_sid and SESSION.CID=user_cid and INSESSION.SID=sess_sid
					and INSESSION.CID=user_cid and SESSION.SPASSWORD=INSESSION.SPASSWORD)
					or (SESSION.SVISIBLE=FALSE and SESSION.SID=sess_sid and INSESSION.SID=sess_sid
					and INSESSION.CID=user_cid and SESSION.SPASSWORD=INSESSION.SPASSWORD));
			if granted>0 then
				delete from SESSION
					where SID=sess_sid;
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
			end if;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Leave-session procedure                                      */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- SID of this session.                                   */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists SESSION_LEAVE;
delimiter $$
create procedure SESSION_LEAVE(IN user_cid int, IN user_pswd char(32), IN sess_sid int, OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			set granted=(select count(SESSION.SID) from SESSION, INSESSION
				where SESSION.SVISIBLE=TRUE and SESSION.SID=sess_sid and SESSION.CID!=user_cid
					and INSESSION.SID=sess_sid and INSESSION.CID=user_cid and SESSION.SPASSWORD=INSESSION.SPASSWORD);
			if granted>0 then
				delete from INSESSION
					where INSESSION.SID=sess_sid and INSESSION.CID=user_cid;
				update SESSION set SESSION.SACTIVE=CURRENT_TIMESTAMP
					where SESSION.SID=sess_sid;
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
			end if;
		end if;
		commit;
	end $$
delimiter ;
