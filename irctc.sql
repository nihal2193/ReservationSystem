CREATE TABLE trains_info
(
  tnum character varying(20) NOT NULL,
  tname text NOT NULL,
  trntype character varying(10) NOT NULL,
  tzone character varying(10) NOT NULL,
  src character varying(10) NOT NULL,
  deptime text NOT NULL,
  dest character varying(10) NOT NULL,
  arrtime time without time zone NOT NULL,
  duration text NOT NULL,
  halt integer NOT NULL,
  sun character(1) NOT NULL,
  mon character(1) NOT NULL,
  tue character(1) NOT NULL,
  wed character(1) NOT NULL,
  thu character(1) NOT NULL,
  fri character(1) NOT NULL,
  sat character(1) NOT NULL,
  a1 integer NOT NULL,
  fc integer NOT NULL,
  a2 integer NOT NULL,
  a3 integer NOT NULL,
  cc integer NOT NULL,
  sl integer NOT NULL,
  s2 integer NOT NULL,
  distance integer NOT NULL,
  avgspeed integer NOT NULL,
  CONSTRAINT trains_info_pkey PRIMARY KEY (tnum)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE trains_info
  OWNER TO postgres;


CREATE TABLE trains_stations
(
  tnum character varying(20) NOT NULL references trains_info(tnum) on delete cascade,
  stncode character varying(10) NOT NULL references stations(stncode) ,
  arrtime text NOT NULL,
  deptime text NOT NULL,
  halttime integer NOT NULL,
  dist integer NOT NULL,
  day integer NOT NULL,
  primary key (tnum,stncode)
)
;

create table tickets 
(
pnr bigint not null primary key,
dob date not null,
doj date not null,
bs varchar(10) not null,
ds varchar(10) not null,
fare real not null, 
status int not null,
class char(2) not null,
no_of_seats int not null

);


-- create table booked_seat
-- (
-- tnum varchar(20) not null,
-- clas char(2) not null,
-- seat_no int not null,
-- primary key (tnum,clas,seat_no)


-- )
-- ;

create table passengers 
(
pid serial primary key,
"name" text not null,
age int not null,
gender char(1) not null,
seat_no int not null,
check (gender = 'M' Or gender = 'F' or gender = 'O' )
)
;

create table users
(
u_name varchar(20) primary key,
passwd varchar(32) not null,
email text not null,
contact bigint not null,
address text not null,
gender char(1) not null,
dob date not null,
check (gender = 'M' Or gender = 'F' or gender = 'O' )
)
;

alter table users  add column  hint varchar(40);


-- create table passengers_booked_seat
-- (
-- pnr varchar(14) references tickets(pnr) on delete cascade,
-- tnum varchar(10) references trains_info(tnum) on delete cascade,
-- clas char(2) not null,
-- seat_no int not null,
-- primary key (pnr,tnum,clas,seat_no)
-- );


-- Table: tickets_users

-- DROP TABLE tickets_users;

CREATE TABLE tickets_users
(
  u_name character varying(20) NOT NULL,
  trans_id character varying(14) NOT NULL,
  pnr bigint,
  CONSTRAINT tickets_users_pkey PRIMARY KEY (u_name,pnr),
  CONSTRAINT tickets_users_pnr_fkey FOREIGN KEY (pnr)
      REFERENCES tickets (pnr) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT tickets_users_u_name_fkey FOREIGN KEY (u_name)
      REFERENCES users (u_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE tickets_users
  OWNER TO postgres;



CREATE TABLE date
(
  date date NOT NULL,
  day character(3) NOT NULL,
  CONSTRAINT date_pkey PRIMARY KEY (date)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE date
  OWNER TO postgres;


create table no_of_seats
(
	tnum varchar(20) references trains_info(tnum) on delete cascade,
	stncode character varying(10) NOT NULL references stations(stncode) ,
	"date" date not null references date(date) on delete cascade,
	a1 integer NOT NULL,
	fc integer NOT NULL,
	a2 integer NOT NULL,
	a3 integer NOT NULL,
	cc integer NOT NULL,
	sl integer NOT NULL,
	s2 integer NOT NULL,
	primary key (tnum,stncode,"date")
);  



CREATE TABLE tickets_trains
(
  pnr bigint NOT NULL,
  tnum character varying(20) NOT NULL,
  CONSTRAINT tickets_trains_pkey PRIMARY KEY (pnr),
  CONSTRAINT tickets_trains_pnr_fkey FOREIGN KEY (pnr)
      REFERENCES tickets (pnr) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE cascade,
  CONSTRAINT tickets_trains_tnum_fkey FOREIGN KEY (tnum)
      REFERENCES trains_info (tnum) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE cascade
)
WITH (
  OIDS=FALSE
);
ALTER TABLE tickets_trains
  OWNER TO postgres;

--update trains table
update trains_info 
	set a1='-1', a2='-1',a3='-1',sl='-1',cc='-1',s2='-1',fc='-1' where fc='0' and s2='0' and cc='0' and sl='0' and a3='0' and s2='0' and s1='0';
	

-- Table: tickets_passengers
CREATE TABLE tickets_passengers
(
  pnr bigint NOT NULL,
  pid bigint NOT NULL,
  CONSTRAINT tickets_passengers_pkey PRIMARY KEY (pnr, pid),
  CONSTRAINT tickets_passengers_pid_fkey FOREIGN KEY (pid)
      REFERENCES passengers (pid) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE cascade,
  CONSTRAINT tickets_passengers_pnr_fkey FOREIGN KEY (pnr)
      REFERENCES tickets (pnr) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE tickets_passengers
  OWNER TO postgres;
