
use DBChat;

set GLOBAL event_scheduler=1;

drop event if exists PURGE_EVENT;
delimiter $$
create event PURGE_EVENT
	on schedule every 1 day starts CURRENT_TIMESTAMP
	do
	begin
		start transaction;
		set @nowtimestamp=CURRENT_TIMESTAMP;
		delete from SESSION where
			TIMESTAMPDIFF(WEEK, SACTIVE, @nowtimestamp)>=1;
		delete from CLIENT where
			TIMESTAMPDIFF(MONTH, CACTIVE, @nowtimestamp)>=1;
		delete from MSG where
			TIMESTAMPDIFF(WEEK, SBIRTH, @nowtimestamp)>=1;
		delete from FRIENDAPPLICATION where
			TIMESTAMPDIFF(WEEK, FAACTIVE, @nowtimestamp)>=1;
		commit;
	end $$
delimiter ;
