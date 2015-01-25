<?php
$no_db_error=1;
require_once("functions.inc.php");
if (system_value('version')<1){
@mysql_query("create database $mysql_database");
if(!@mysql_select_db($mysql_database)){
echo 'Error creating '.$mysql_database.'. Please check your settings in conf.inc.php';
exit;
}
@mysql_query("CREATE TABLE ".mysql_prefix."ips (
ip char(64) not null,
`note` VARCHAR( 255 ) NOT NULL,
primary key (ip)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."plugins (
classname char(64) not null,
directory char(64) not null,
runner tinyint not null,
primary key (classname),
key runner(runner)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."clicks_being_processed ( 
username char(16) not null, 
type char(6) not null, 
amount bigint not null,
count smallint not null, 
primary key (username,type) 
) "); 
@mysql_query("create table ".mysql_prefix."chat_messages (
  time bigint not null,
  user char(16) not null,
  color char(6) not null,
  message blob not null,
  room char(16) not null,
  key time(time),
  key room(room)
) ");
@mysql_query("create table ".mysql_prefix."chat_users (
  time bigint not null,
  user char(16) not null,
  color char(6) not null,
  room char(16) not null,
  key time(time),
  unique room(room,user)
) ");

  
@mysql_query("CREATE TABLE ".mysql_prefix."access_log (
  time timestamp not null,
  value blob,
  key time(time)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."autosurf (    
  username char(16) NOT NULL,
  url char(255) NOT NULL,
  approved tinyint NOT NULL,
  active tinyint not null,
  time bigint not null,
  runad bigint not null,        
  hits int not null,
  abuse char(16) not null,
  quantity int not null,
  unique uniqueness(username,url), 
  KEY runad(runad) 
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."autosurf2 (
  username char(16) NOT NULL,
  url char(255) NOT NULL,
  approved tinyint NOT NULL,
  active tinyint not null,
  time bigint not null,
  runad bigint not null,
  hits int not null,
  abuse char(16) not null,
  quantity int not null,
  unique uniqueness(username,url),
  KEY runad(runad)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."autosurf3 (
  username char(16) NOT NULL,
  url char(255) NOT NULL,
  approved tinyint NOT NULL,
  active tinyint not null,
  time bigint not null,
  runad bigint not null,
  hits int not null,
  abuse char(16) not null,
  quantity int not null,
  unique uniqueness(username,url),
  KEY runad(runad)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."autosurf4 (
  username char(16) NOT NULL,
  url char(255) NOT NULL,
  approved tinyint NOT NULL,
  active tinyint not null,
  time bigint not null,
  runad bigint not null,
  hits int not null,
  abuse char(16) not null,
  quantity int not null,
  unique uniqueness(username,url),
  KEY runad(runad)
) ");

@mysql_query("create table ".mysql_prefix."sessions( 
sessionid    char(64) not null, 
lastupdated  bigint not null,
datavalue    blob not null,
primary key (sessionid)
)
");
@mysql_query("CREATE TABLE ".mysql_prefix."emails (
address char(64) not null,
primary key (address)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."browsers (
agent char(64) not null,
primary key (agent)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."system_values (
name char(32) not null,
value blob not null,
primary key (name)) ");
@mysql_query("CREATE TABLE ".mysql_prefix."clicks_to_process (
username char(16) not null,
type char(6) not null,
amount bigint not null,
key usertype(username,type)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."cronjobs (
name char(32) not null,
value bigint not null,
primary key (name)) ");
@mysql_query("CREATE TABLE ".mysql_prefix."latest_stats (
username char(16) not null,
id char(255) not null,
time char(255) not null,
type char(255) not null,
primary key (username)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."free_refs (
username char(16) not null,
key username(username)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."redemptions (
  id int NOT NULL AUTO_INCREMENT,
  description char(255) not null,
  special blob not null,
  amount bigint not null,
  type char(6) not null,
  auto char(3) not null,
  phpcode blob not null,
  group_order char(1) not null,
  sub_group_order char(1) not null,
  mtype char(16) not null, 
  upgrade_acct char(16) not null,
  `upgrade_autoexpire` VARCHAR( 100 ) NOT NULL,
  key order_key(group_order,sub_group_order,type,amount),
  primary key (id),
  key description(description)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."member_types (
  description char(16) not null,
  commission_amount int not null,
  free_refs tinyint not null,
    primary key (description)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."last_login (
  username char(16) not null,
  time bigint not null,
  computerid char(32) not null,
  ip_host char(64) not null,
  browser char(255) not null,
  cash_clicks smallint unsigned not null,
  points_clicks smallint unsigned not null,
  last_click bigint not null,
  bad_turing smallint unsigned not null,
  turing_time bigint not null,
  turing_numbers char(255) not null,
  cheat_links smallint unsigned not null,
  as_start_ad int not null,
  as2_start_ad int not null,
  as3_start_ad int not null,
  as4_start_ad int not null,
  primary key (username),
  key time(time),
  key computerid(computerid),
  key ip_host(ip_host),
  key browser(browser)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."keywords (
  keyword char(32) not null,
  preselected tinyint(1) not null default 0,
  keycount int not null,
  primary key (keyword)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."payment_types (
  type char(16) not null,
  currency char(16) not null,
  process_fee  decimal(10,2) not null, 
  primary key (type)
) ");

@mysql_query("CREATE TABLE ".mysql_prefix."users (
  username char(16) NOT NULL, 
  password char(16) NOT NULL,
  email char(64) NOT NULL, 
  upline char(16) NOT NULL, 
  referrer char(16) NOT NULL, 
  signup_ip_host char(64) NOT NULL, 
  first_name char(32) NOT NULL, 
  last_name char(32) NOT NULL, 
  address char(128) NOT NULL, 
  city char(64) NOT NULL, 
  state char(32) NOT NULL, 
  state_other char(32) not null,
  zipcode char(16) NOT NULL, 
  country char(32) NOT NULL, 
  signup_date datetime not null,
  pay_type char(16) not null,
  pay_account char(64) not null,
  free_refs tinyint not null,
  commission_amount int not null,
  disable_turing tinyint not null, 
  account_type char(16) not null,
  vacation date not null,
  email_setting tinyint not null default 2,
  http_referer char(128) not null,
  rebuild_stats_cache tinyint not null default 1,
  last_email bigint not null,
  `upgrade_expires` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (username),
  KEY password (password(8)),
  unique email (email),
  KEY referrer (referrer(8)),
  KEY upline (upline(8)),
  KEY signup_ip_host (signup_ip_host(16)),
  KEY first_name (first_name(16)),
  KEY last_name (last_name(16)),
  KEY state (state(16)),
  KEY country (country(16)),
  key account_type (account_type(8)),
  KEY pay_account(pay_account(16)),
  key free_refs(free_refs),
  key email_setting(email_setting),
  key rebuild_stats_cache(rebuild_stats_cache),
  key vacation(vacation),
  key last_email(last_email)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."countries (
country char(32) NOT NULL,
ccount int not null,
PRIMARY KEY  (country)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."states (     
state char(32) NOT NULL,       
PRIMARY KEY  (state)       
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."interests (
  username char(16) not null,
  keywords blob not null,
  primary KEY (username)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."notes (
  username char(16) not null,
  notes blob not null,
  primary KEY (username)
) "); 
@mysql_query("CREATE TABLE ".mysql_prefix."accounting (
  transid char(26) not null, 
  unixtime bigint not null,
  username char(16) not null,
  description char(50) not null,
  amount bigint not null,
  type char(6) not null,
  time timestamp not null,
  primary key (transid),
  KEY username(username),
  unique uniqueness(unixtime,username,description,type),
  key description(description),
  key type(type)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."rotating_ads (
  bannerid int NOT NULL AUTO_INCREMENT,
  id char(16) NOT NULL, 
  description char(255) NOT NULL,
  image_url char(255) NOT NULL,
  img_width int not null,
  img_height int not null,
  site_url blob NOT NULL,
  alt_text char(255) NOT NULL,
  text_ad char(255) NOT NULL,
  html blob NOT NULL,
  category char(16) NOT NULL,
  run_quantity bigint NOT NULL,
  run_type char(10), 
  `time` TIMESTAMP NOT NULL DEFAULT 0,
  views bigint NOT NULL,
  clicks int NOT NULL,
  popupurl char(255) NOT NULL,
  popupwidth int not null,
  popupheight int not null,
  popuptype char(8) not null,
  runad bigINT not null, 
  PRIMARY KEY (bannerid),
  KEY id(id),
  KEY showitkey(category,runad),
  key runad(runad) 
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."ptc_ads (
  ptcid int NOT NULL AUTO_INCREMENT,
  id char(16) NOT NULL,
  description char(255) NOT NULL,
  image_url char(255) NOT NULL,
  img_width int not null,
  img_height int not null,
  site_url blob NOT NULL,
  alt_text char(255) NOT NULL,
  text_ad char(255) not null,
  html blob NOT NULL,
  category char(16) NOT NULL,
  run_quantity bigint NOT NULL,
  run_type char(10),
  time timestamp NOT NULL,
  views bigint NOT NULL,
  clicks int NOT NULL,
  value bigint not null,
  vtype char(6) not null,
  timer int not null, 
  hrlock int not null,
  `cheat_link` TINYINT(1) NOT NULL DEFAULT 0,
  `targeting` BLOB NOT NULL,
  `creation` TIMESTAMP NOT NULL,
  PRIMARY KEY (ptcid),
  KEY id(id),
  KEY description(description),
  KEY category(category),
  KEY run_quantity(run_quantity),
  KEY run_type(run_type),
  KEY views(views),
  KEY clicks(clicks),
  KEY value(value),
  KEY vtype(vtype),
  KEY time(time),
  KEY hrlock(hrlock)
) ");
@mysql_query("CREATE TABLE `".mysql_prefix."ptc_targetgroups` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `name` VARCHAR( 50 ) NOT NULL ,
            `targeting` BLOB NOT NULL ,
            PRIMARY KEY ( `id` )
        );");
@mysql_query("CREATE TABLE ".mysql_prefix."reviews_to_process (
 id int not null,
 username char(16) not null,
 email blob,
 rate tinyint not null,
 KEY id(id),
 KEY username(username),
 unique uniqueness(username,id)
) ");

@mysql_query("CREATE TABLE ".mysql_prefix."signups_to_process (
 id int not null,
 username char(16) not null,
 email blob,
 KEY id(id),
 KEY username(username),
 unique uniqueness(username,id)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."review_ads (
 id int NOT NULL AUTO_INCREMENT,
 username char(16) NOT NULL,
 description char(255) NOT NULL,
 creditul tinyint not null,        
 image_url char(255) NOT NULL,
 img_width int not null,
 img_height int not null,
 site_url char(255) NOT NULL,
 alt_text char(255) NOT NULL,
 html blob NOT NULL,
 category char(16) NOT NULL,
 run_quantity bigint NOT NULL,
 run_type tinyint not null,    
 time timestamp NOT NULL,
 views bigint NOT NULL,
 reviews int NOT NULL,
 value bigint not null,
 vtype char(6) not null,
 rating tinyint not null,
 PRIMARY KEY (id),
 KEY username(username),
 KEY description(description),
 KEY category(category),
 KEY run_quantity(run_quantity),
 KEY run_type(run_type),
 KEY views(views),        
 KEY value(value),
 KEY vtype(vtype)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."pts_ads (
 ptsid int NOT NULL AUTO_INCREMENT,
 id char(16) NOT NULL,
 description char(255) NOT NULL,
 creditul char(3),
 image_url char(255) NOT NULL,
 img_width int not null,
 img_height int not null,
 site_url char(255) NOT NULL,
 alt_text char(255) NOT NULL,
 html blob NOT NULL,
 category char(16) NOT NULL,
 run_quantity bigint NOT NULL,
 run_type char(10),
 time timestamp NOT NULL,
 views bigint NOT NULL,
 signups int NOT NULL,
 value bigint not null,
 vtype char(6) not null,
 PRIMARY KEY (ptsid),
 KEY id(id),
 KEY description(description),
 KEY category(category),
 KEY run_quantity(run_quantity),
 KEY run_type(run_type),
 KEY views(views),
 KEY signups(signups),
 KEY value(value),
 KEY vtype(vtype),
 KEY time(time)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."email_ads (
  emailid bigint not null,
  id char(16) NOT NULL,
  description char(255) NOT NULL,
  site_url blob NOT NULL,
  ad_text blob NOT NULL,
  run_quantity bigint NOT NULL,
  run_type char(10),
  time timestamp NOT NULL,
  clicks int NOT NULL,
  value bigint not null,
  vtype char(6) not null,
  timer int not null,
  login char(6) not null,
  hrlock SMALLINT not null,
  cheat_link tinyint not null, 
  `creation_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_sent` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (emailid),
  KEY id(id),
  KEY description(description),
  KEY run_quantity(run_quantity),
  KEY run_type(run_type),
  KEY clicks(clicks),
  KEY creation_date(creation_date),
  key hrlock(hrlock),
  KEY time(time)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."paid_clicks (
  id int NOT NULL,
  username char(16) NOT NULL,
  value bigint not null,
  vtype char(6) not null,
  time timestamp not null,
  KEY id(id),
  KEY username(username),
  KEY value(value),
  KEY vtype(vtype),
  KEY time(time)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."mass_mailer (
  `time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  massmailid int NOT NULL AUTO_INCREMENT, 
  subject char(64) NOT NULL,
  start int NOT NULL,
  stop int not null,
  current int not null,
  ad_text blob not null,
  is_html char(1) not null,
  keywords blob not null,
  inboxonly char(1) not null,
  charset char(16) not null,
  primary key (massmailid),
  KEY subject(subject),
  KEY start(start),
  KEY stop(stop),
  KEY current(current),
  KEY time(time)
) ");

@mysql_query("CREATE TABLE ".mysql_prefix."inbox_mails (
 time timestamp not null,
 id int not null,
 subject char(64) NOT NULL,
 message blob not null,
 primary key (id),
 KEY time(time)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."user_inbox (
username char(17) NOT NULL,
mails blob not null, 
primary key (username)
) ");
@mysql_query("CREATE TABLE ".mysql_prefix."levels (
  username char(16) NOT NULL,
  upline char(16) NOT NULL,
  level int NOT NULL,
  unique uniqueness(username,upline),
  KEY username(username),
  KEY upline(upline),
  KEY level(level)
) ");
$domain=system_value('domain');

require_once("create_defaults.inc.php");
require_once("create_states.inc.php");
require_once("create_keywords.inc.php");
require_once("create_countries.inc.php");

@mysql_query('insert into '.mysql_prefix.'system_values set name="checkturing",value="YES"');

        @mysql_query("CREATE TABLE ".mysql_prefix."clicks_being_processed ( 
            username char(16) not null, 
            type char(6) not null, 
            amount bigint not null,
            count smallint not null, 
            primary key (username,type) 
            ) "); 
        @mysql_query('alter table '.mysql_prefix.'last_login modify as_start_ad int not null');
        @mysql_query('alter table '.mysql_prefix.'last_login add as2_start_ad int not null');
        @mysql_query('alter table '.mysql_prefix.'last_login add as3_start_ad int not null');
        @mysql_query('alter table '.mysql_prefix.'last_login add as4_start_ad int not null');
        @mysql_query("alter table ".mysql_prefix."mass_mailer add charset char(16) not null");
        @mysql_query("update ".mysql_prefix."mass_mailer set charset='iso-8859-1' where charset=''");
        @mysql_query("CREATE TABLE ".mysql_prefix."autosurf2 (
            username char(16) NOT NULL,
            url char(255) NOT NULL,
            approved tinyint NOT NULL,
            active tinyint not null,
            time bigint not null,
            runad bigint not null,
            hits int not null,
            abuse char(16) not null,
            quantity int not null,
            unique uniqueness(username,url),
            KEY runad(runad)
            ) ");
        @mysql_query("CREATE TABLE ".mysql_prefix."autosurf3 (
            username char(16) NOT NULL,
            url char(255) NOT NULL,
            approved tinyint NOT NULL,
            active tinyint not null,
            time bigint not null,
            runad bigint not null,
            hits int not null,
            abuse char(16) not null,
            quantity int not null,
            unique uniqueness(username,url),
            KEY runad(runad)
            ) ");
        @mysql_query("CREATE TABLE ".mysql_prefix."autosurf4 (
            username char(16) NOT NULL,
            url char(255) NOT NULL,
            approved tinyint NOT NULL,
            active tinyint not null,
            time bigint not null,
            runad bigint not null,
            hits int not null,
            abuse char(16) not null,
            quantity int not null,
            unique uniqueness(username,url),
            KEY runad(runad)
            ) ");
        
        @mysql_query('alter table '.mysql_prefix.'redemptions add group_order char(1) not null');
        @mysql_query('alter table '.mysql_prefix.'redemptions add sub_group_order char(1) not null');
        @mysql_query('alter table '.mysql_prefix.'redemptions add mtype char(16) not null');
        @mysql_query('alter table '.mysql_prefix.'redemptions add upgrade_acct char(16) not null');
        @mysql_query('create index group_order on '.mysql_prefix.'redemptions(group_order,sub_group_order,type,amount)');
        @mysql_query('alter table '.mysql_prefix.'countries add ccount int not null');
        @mysql_query('insert into system_values set name="dldescription",value="Downline Earnings"');
        @mysql_query('update accounting set description="Downline Earnings" where description like "#DOWNLINE-%"');
        @mysql_query('update accounting set description="Paid Mail Earnings" where description like "#SELF-%"');
        @mysql_query('insert into system_values set name="pmdescription",value="Paid Mail Earnings"');
        @mysql_query('insert into system_values set name="ptcdescription",value="Paid To Click Earnings"');
        @mysql_query('insert into system_values set name="pspdescription",value="Paid Start Page Earnings"');
        @mysql_query('insert into system_values set name="ascdescription",value="Auto/Manual Surf Earnings"');
        @mysql_query('insert into system_values set name="asddescription",value="Auto/Manual Surf Ad credits"');
        @mysql_query('insert into system_values set name="convertpoints",value="points converted to cash"');
        @unlink('../sandr.pl');
        @mysql_query('insert into '.mysql_prefix.'system_values set name="emailcharset",value="iso-8859-1"');
        @mysql_query('insert into '.mysql_prefix.'system_values set name="charset",value="iso-8859-1"');
        @mysql_query("create table ".mysql_prefix."chat_messages (
        time bigint not null,
        user char(16) not null,
        color int not null,
        message blob not null,
        room char(16) not null,
        key time(time),
        key room(room)
        ) ");
        @mysql_query("create table ".mysql_prefix."chat_users (
        time bigint not null,
        user char(16) not null,
        color int not null,
        room char(16) not null,
        key time(time),
        unique room(room,user)
        ) ");
        @mysql_query('alter table '.mysql_prefix.'user_inbox modify username char(17) not null');
        @mysql_query('alter table '.mysql_prefix.'users add last_email bigint not null');
        @mysql_query('create index last_email on '.mysql_prefix.'users(last_email)');
        @mysql_query('alter table '.mysql_prefix.'chat_users modify color char(6) not null');
        @mysql_query('alter table '.mysql_prefix.'chat_messages modify color char(6) not null');
        @mysql_query('
            CREATE TABLE `'.mysql_prefix.'reminders` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `message` BLOB NOT NULL ,
            `due` VARCHAR( 15 ) NOT NULL ,
            `done` TIMESTAMP NOT NULL,
            UNIQUE (
            `id`
                )
            )');
        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`) VALUES ('site_offline')");
        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`) VALUES ('site_offline_message')");
        @mysql_query("UPDATE `".mysql_prefix."system_values` SET value = '0' WHERE name = 'site_offline'");    
        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`) VALUES ('turinglinksize')");
        @mysql_query("UPDATE `".mysql_prefix."system_values` SET value = '100' WHERE name = 'turinglinksize'");
        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`, `value`) VALUES ('bklocalpath', '../mysql_restore/')");

        @mysql_query("CREATE TABLE `".mysql_prefix."scheduler_emailsets` 
                (
                    `title` VARCHAR( 80 ) NOT NULL ,
                    `html` CHAR( 1 ) NOT NULL DEFAULT 'N' ,
                    `inbox` CHAR( 1 ) NOT NULL DEFAULT 'N' ,
                    `charset` VARCHAR( 20 ) NOT NULL DEFAULT 'iso-8859-1' ,
                    `frequenzy` INT( 11 ) NOT NULL DEFAULT '1440' , 
                    `header` BLOB NOT NULL ,
                    `footer` BLOB NOT NULL ,
                    `separator` BLOB NOT NULL ,
                    UNIQUE (
                        `title`
                        )
                );");
        @mysql_query("CREATE TABLE `".mysql_prefix."scheduler_emails` 
                (
                    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                    `headerset` VARCHAR( 80 ) NOT NULL , 
                    `subject` VARCHAR( 64 ) NOT NULL , 
                    `charset` VARCHAR( 64 ) NOT NULL , 
                    `is_html` CHAR( 1 ) NOT NULL , 
                    `status` VARCHAR( 10 ) NOT NULL , 
                    `content` BLOB NOT NULL ,
                    `keywords` BLOB NOT NULL ,
                    `inboxonly` INT NOT NULL DEFAULT '0',
                    `frequenzy` INT UNSIGNED NOT NULL DEFAULT '1440',
                    `last_sent` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `next_send` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `receivers_query` BLOB NOT NULL ,
                    UNIQUE (
                    `id`
                    )
                );");
        @mysql_query("INSERT INTO `".mysql_prefix."scheduler_emailsets` (`title` ,`html` ,`inbox` ,`charset` ,`frequenzy`) VALUES ('default', 'N', 'N', 'iso-8859-1', '1440');");

        @mysql_query("CREATE TABLE `".mysql_prefix."visitors_online` (
                            `time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
                            `username` VARCHAR( 50 ) NOT NULL ,
                            `ip` VARCHAR( 50 ) NOT NULL ,
                            `request` VARCHAR( 50 ) NULL ,
                            `http_referer` VARCHAR( 70 ) NOT NULL,
                            UNIQUE (`ip`)
                        )");

        @mysql_query("CREATE TABLE `".mysql_prefix."abuse` (
                        `id` int(11) NOT NULL auto_increment,
                        `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
                        `username` varchar(255) NOT NULL default '',
                        `type` varchar(10) NOT NULL default '',
                        `ad` bigint(20) NOT NULL default 0,
                        `reason` blob NOT NULL,
                            UNIQUE KEY `id` (`id`)
                    ) ;
                ");
    


require_once("version.php");
}
