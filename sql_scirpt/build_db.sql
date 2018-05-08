/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2018/5/7 20:07:49                            */
/*==============================================================*/


drop table if exists CLIENT;

drop table if exists FRIENDAPPLICATION;

drop table if exists INSESSION;

drop table if exists KNOWS;

drop table if exists MSG;

drop table if exists SESSION;

/*==============================================================*/
/* Table: CLIENT                                                */
/*==============================================================*/
create table CLIENT
(
   CID                  int not null auto_increment,
   CNAME                char(32) not null,
   CPASSWORD            char(32),
   CACTIVE              datetime not null,
   primary key (CID)
);

/*==============================================================*/
/* Table: FRIENDAPPLICATION                                     */
/*==============================================================*/
create table FRIENDAPPLICATION
(
   FAID                 int not null auto_increment,
   CID                  int not null,
   CLI_CID              int not null,
   FAACTIVE             datetime not null,
   primary key (FAID)
);

/*==============================================================*/
/* Table: INSESSION                                             */
/*==============================================================*/
create table INSESSION
(
   CID                  int not null,
   SID                  int not null,
   primary key (CID, SID)
);

/*==============================================================*/
/* Table: KNOWS                                                 */
/*==============================================================*/
create table KNOWS
(
   CID                  int not null,
   CLI_CID              int not null,
   primary key (CID, CLI_CID)
);

/*==============================================================*/
/* Table: MSG                                                   */
/*==============================================================*/
create table MSG
(
   MID                  int not null auto_increment,
   SID                  int not null,
   CID                  int not null,
   MTEXT                text not null,
   MBIRTH               datetime not null,
   primary key (MID)
);

/*==============================================================*/
/* Table: SESSION                                               */
/*==============================================================*/
create table SESSION
(
   SID                  int not null auto_increment,
   CID                  int not null,
   SNAME                char(32) not null,
   SPASSWORD            char(32),
   SVISIBLE             bool not null,
   SACTIVE              datetime not null,
   primary key (SID)
);

alter table FRIENDAPPLICATION add constraint FK_FAACTIVE foreign key (CID)
      references CLIENT (CID) on delete restrict on update restrict;

alter table FRIENDAPPLICATION add constraint FK_FAPASSIVE foreign key (CLI_CID)
      references CLIENT (CID) on delete restrict on update restrict;

alter table INSESSION add constraint FK_INSESSION foreign key (CID)
      references CLIENT (CID) on delete restrict on update restrict;

alter table INSESSION add constraint FK_INSESSION2 foreign key (SID)
      references SESSION (SID) on delete restrict on update restrict;

alter table KNOWS add constraint FK_KNOWS foreign key (CID)
      references CLIENT (CID) on delete restrict on update restrict;

alter table KNOWS add constraint FK_KNOWS2 foreign key (CLI_CID)
      references CLIENT (CID) on delete restrict on update restrict;

alter table MSG add constraint FK_SENTBY foreign key (CID)
      references CLIENT (CID) on delete restrict on update restrict;

alter table MSG add constraint FK_TOSESSION foreign key (SID)
      references SESSION (SID) on delete restrict on update restrict;

alter table SESSION add constraint FK_MANAGEDBY foreign key (CID)
      references CLIENT (CID) on delete restrict on update restrict;

