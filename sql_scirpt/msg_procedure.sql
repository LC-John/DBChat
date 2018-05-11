
use DBChat;

/*==============================================================*/
/* Send-masage procedure                                        */
/* IN <- CID of this user.                                      */
/*    <- Password of this new user.                             */
/*    <- SID of this session.                                   */
/*    <- Mesage text.                                           */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists MSG_SEND;
delimiter $$
create procedure MSG_SEND(IN user_cid int, IN user_pswd char(32), IN sess_sid int, IN msg_txt text, OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			set granted=(select count(*) from INSESSION
				where CID=user_cid and SID=sess_sid);
			if granted>0 then
				insert into MSG(SID, CID, MTEXT) values (sess_sid, user_cid, msg_txt);
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
				update SESSION set SACTIVE=CURRENT_TIMESTAMP
					where SID=sess_sid;
			end if;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Get-masage procedure                                         */
/* IN <- CID of this user.                                      */
/*    <- Password of this new user.                             */
/*    <- SID of this session.                                   */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists MSG_GET;
delimiter $$
create procedure MSG_GET(IN user_cid int, IN user_pswd char(32), IN sess_sid int, OUT granted int)
	begin
		declare EXIT HANDLER for SQLEXCEPTION rollback;
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_pswd);
		if granted>0 then
			set granted=(select count(*) from INSESSION
				where CID=user_cid and SID=sess_sid);
			if granted>0 then
				select MID, CID, MTEXT, MBIRTH from MSG
					where SID=sess_sid;
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
				update SESSION set SACTIVE=CURRENT_TIMESTAMP
					where SID=sess_sid;
			end if;
		end if;
		commit;
	end $$
delimiter ;
