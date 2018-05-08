
use DBChat;

/*==============================================================*/
/* Sign-up procedure                                            */
/* IN <- Name of this new user.                                 */
/*    <- Password of this new user.                             */
/* OUT -> Generated CID of this new user, which is the only     */
/*          identification of this user.                        */
/*==============================================================*/
drop procedure if exists USER_SIGN_UP;
delimiter $$
create procedure USER_SIGN_UP(IN user_name char(32), IN user_password char(32), OUT user_cid int)
	begin
		start transaction;
		# Add a temporary flag column in order to find the new user after insertion.
		alter table CLIENT add column __TMP_FLAG_COLUMN__ boolean not null default FALSE;
		insert into CLIENT(CNAME, CPASSWORD, __TMP_FLAG_COLUMN__) values (user_name, user_password, TRUE);
		set user_cid=(select CID from CLIENT where __TMP_FLAG_COLUMN__=TRUE);
		# The user knows himself.
		insert into KNOWS(CID, CLI_CID) values (user_cid, user_cid);
		# Delete the temporary column
		alter table CLIENT drop column __TMP_FLAG_COLUMN__;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Sign-in procedure                                            */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists USER_SIGN_IN;
delimiter $$
create procedure USER_SIGN_IN(IN user_cid int, IN user_password char(32), OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0 then
			update CLIENT set CACTIVE=CURRENT_TIMESTAMP
				where CID=user_cid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Sign-out procedure                                           */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/* OUT -> Authorization granted (correct password) or not.      */
/*          If granted, delete this user.                       */
/*==============================================================*/
drop procedure if exists USER_SIGN_OUT;
delimiter $$
create procedure USER_SIGN_OUT(IN user_cid int, IN user_password char(32), OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0 then
			delete from CLIENT where CID=user_cid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Get-name procedure                                           */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/* OUT -> Authorization granted (correct password) or not.      */
/*     -> If granted, name of this user.                        */
/*==============================================================*/
drop procedure if exists USER_GET_NAME;
delimiter $$
create procedure USER_GET_NAME(IN user_cid int, IN user_password char(32), OUT user_name char(32), OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0 then
			set user_name=(select CNAME from CLIENT
				where CID=user_cid);
			update CLIENT set CACTIVE=CURRENT_TIMESTAMP
				where CID=user_cid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Rename procedure                                             */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- New name of this user.                                 */
/* OUT -> Authorization granted (correct password) or not.      */
/*          If granted, rename this user.                       */
/*==============================================================*/
drop procedure if exists USER_RENAME;
delimiter $$
create procedure USER_RENAME(IN user_cid int, IN user_password char(32), IN user_name char(32), OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0 then
			update CLIENT set CNAME=user_name, CACTIVE=CURRENT_TIMESTAMP
				where CID=user_cid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Change-password procedure                                    */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- New password of this user.                             */
/* OUT -> Authorization granted (correct password) or not.      */
/*          If granted, change the password of this user.       */
/*==============================================================*/
drop procedure if exists USER_CHANGE_PASSWORD;
delimiter $$
create procedure USER_CHANGE_PASSWORD(IN user_cid int, IN user_password char(32), IN new_password char(32), OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0 then
			update CLIENT set CPASSWORD=new_password, CACTIVE=CURRENT_TIMESTAMP
				where CID=user_cid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Get-friend procedure                                         */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- New password of this user.                             */
/* OUT -> Authorization granted (correct password) or not.      */
/* TABLE -> CIDs and names of this user's friends.              */
/*==============================================================*/
drop procedure if exists USER_GET_FRIEND;
delimiter $$
create procedure USER_GET_FRIEND(IN user_cid int, IN user_password char(32), OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0 then
			select CLIENT.CID, CLIENT.CNAME from CLIENT, KNOWS
				where KNOWS.CID=user_cid and KNOWS.CLI_CID=CLIENT.CID;
			update CLIENT set CACTIVE=CURRENT_TIMESTAMP
				where CID=user_cid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Friend-application procedure                                 */
/* IN <- CID of the user who sends the application.             */
/*    <- Password of this user.                                 */
/*    <- CID of the user who would receives the application.    */
/* OUT -> Authorization granted (correct password) or not.      */
/*     -> FAID of this application, if conditions satisfied.    */
/*==============================================================*/
drop procedure if exists USER_FRIEND_APPLICATION;
delimiter $$
create procedure USER_FRIEND_APPLICATION(IN user_cid int, IN user_password char(32), IN fa_cid int, OUT faid int, OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0
			and (select count(*) from KNOWS where (CID=user_cid and CLI_CID=fa_cid)
					or (CID=fa_cid and CLI_CID=user_cid))=0
			and (select count(*) from FRIENDAPPLICATION where (CID=user_cid and CLI_CID=fa_cid)
					or (CID=fa_cid and CLI_CID=user_cid))=0 then
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;

				alter table FRIENDAPPLICATION add column __TMP_FLAG_COLUMN__ boolean not null default FALSE;
				insert into FRIENDAPPLICATION(CID, CLI_CID, __TMP_FLAG_COLUMN__) values (user_cid, fa_cid, TRUE);
				set faid=(select FRIENDAPPLICATION.FAID from FRIENDAPPLICATION
					where FRIENDAPPLICATION.__TMP_FLAG_COLUMN__=TRUE);
				alter table FRIENDAPPLICATION drop column __TMP_FLAG_COLUMN__;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Get-friend-application procedure                             */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/* OUT -> Authorization granted (correct password) or not.      */
/* TABLE -> FAID, receiver's CID and receiver's name of the     */
/*            application.                                      */
/*==============================================================*/
drop procedure if exists USER_GET_FRIEND_APPLICATION;
delimiter $$
create procedure USER_GET_FRIEND_APPLICATION(IN user_cid int, IN user_password char(32), OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0 then
			update CLIENT set CACTIVE=CURRENT_TIMESTAMP
				where CID=user_cid;
			select fa.FAID, fa.CID, a.CNAME from FRIENDAPPLICATION as fa, CLIENT as a
				where fa.CLI_CID=user_cid and fa.CID=a.CID;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Accept-friend-application procedure                          */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- FAID of the friend application.                        */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists USER_ACCEPT_FRIEND_APPLICATION;
delimiter $$
create procedure USER_ACCEPT_FRIEND_APPLICATION(IN user_cid int, IN user_password char(32), IN faid int, OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0
			and (select count(*) from FRIENDAPPLICATION where FAID=faid and CLI_CID=user_cid)>0 then
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
				insert into KNOWS(CID, CLI_CID)
					select fa.CID, fa.CLI_CID from FRIENDAPPLICATION as fa
						where fa.FAID=faid;
				insert into KNOWS(CLI_CID, CID)
					select fa.CID, fa.CLI_CID from FRIENDAPPLICATION as fa
						where fa.FAID=faid;
				delete from FRIENDAPPLICATION where FAID=faid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Refuse-friend-application procedure                          */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- FAID of the friend application.                        */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists USER_REFUSE_FRIEND_APPLICATION;
delimiter $$
create procedure USER_REFUSE_FRIEND_APPLICATION(IN user_cid int, IN user_password char(32), IN faid int, OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0
			and (select count(*) from FRIENDAPPLICATION where FAID=faid and CLI_CID=user_cid)>0 then
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
				delete from FRIENDAPPLICATION where FAID=faid;
		end if;
		commit;
	end $$
delimiter ;

/*==============================================================*/
/* Delete-friend procedure                                      */
/* IN <- CID of a user.                                         */
/*    <- Password of this user.                                 */
/*    <- CID of a friend.                                       */
/* OUT -> Authorization granted (correct password) or not.      */
/*==============================================================*/
drop procedure if exists USER_DELETE_FRIEND;
delimiter $$
create procedure USER_DELETE_FRIEND(IN user_cid int, IN user_password char(32), IN other_user_id int, OUT granted int)
	begin
		start transaction;
		set granted=(select count(CID) from CLIENT
			where CID=user_cid and CPASSWORD=user_password);
		if granted>0
			and (select count(*) from FRIENDAPPLICATION where FAID=faid and CLI_CID=user_cid)>0 then
				update CLIENT set CACTIVE=CURRENT_TIMESTAMP
					where CID=user_cid;
				delete from KNOWS
					where (CID=user_cid and CLI_CID=other_user_id)
						or (CID=other_user_id and CLI_CID=user_cid);
		end if;
		commit;
	end $$
delimiter ;
