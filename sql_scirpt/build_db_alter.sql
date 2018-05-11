
use DBChat;

alter table INSESSION add column SPASSWORD char(32);

alter table INSESSION drop foreign key FK_INSESSION;
alter table INSESSION add constraint FK_INSESSION foreign key (CID)
      references CLIENT (CID) on delete cascade on update cascade;

alter table INSESSION drop foreign key FK_INSESSION2;
alter table INSESSION add constraint FK_INSESSION2 foreign key (SID)
      references SESSION (SID) on delete cascade on update cascade;

alter table KNOWS drop foreign key FK_KNOWS;
alter table KNOWS add constraint FK_KNOWS foreign key (CID)
      references CLIENT (CID) on delete cascade on update cascade;

alter table KNOWS drop foreign key FK_KNOWS2;
alter table KNOWS add constraint FK_KNOWS2 foreign key (CLI_CID)
      references CLIENT (CID) on delete cascade on update cascade;

alter table MSG drop foreign key FK_SENTBY;
alter table MSG add constraint FK_SENTBY foreign key (CID)
      references CLIENT (CID) on delete cascade on update cascade;

alter table MSG drop foreign key FK_TOSESSION;
alter table MSG add constraint FK_TOSESSION foreign key (SID)
      references SESSION (SID) on delete cascade on update cascade;

alter table SESSION drop foreign key FK_MANAGEDBY;
alter table SESSION add constraint FK_MANAGEDBY foreign key (CID)
      references CLIENT (CID) on delete cascade on update cascade;

alter table FRIENDAPPLICATION drop foreign key FK_FAACTIVE;
alter table FRIENDAPPLICATION add constraint FK_FAACTIVE foreign key (CID)
      references CLIENT (CID) on delete cascade on update cascade;

alter table FRIENDAPPLICATION drop foreign key FK_FAPASSIVE;
alter table FRIENDAPPLICATION add constraint FK_FAPASSIVE foreign key (CLI_CID)
      references CLIENT (CID) on delete cascade on update cascade;

alter table CLIENT drop column CACTIVE;
alter table CLIENT add column CACTIVE timestamp not null DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

alter table MSG drop column MBIRTH;
alter table MSG add column MBIRTH timestamp not null default CURRENT_TIMESTAMP;

alter table SESSION drop column SACTIVE;
alter table SESSION add column SACTIVE timestamp not null DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

alter table FRIENDAPPLICATION drop column FAACTIVE;
alter table FRIENDAPPLICATION add column FAACTIVE timestamp not null DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
