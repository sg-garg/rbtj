<?php
if(!defined("autoupdater"))
{
    include("functions.inc.php");
    $title='MySQL update';
    admin_login();

    //------------------------------------------------------
    // Verify that we are on licensed site
    //------------------------------------------------------
    $fp3 = @fsockopen("cashcrusadersoftware.com", 80,$errno, $errstr, 10);
    echo "Verifying license: ";
    fputs($fp3,"GET /verifylicense.php?domain=".system_value('domain')."&key=".key." HTTP/1.0\r\nHost: cashcrusadersoftware.com\r\n\r\n");
    socket_set_timeout($fp3, 10);
    $tmp = "";
    $write = 0;
    while(!feof($fp3)) 
    {
        $line = fread($fp3, 1024);
        $tmp .= $line;
    }
    $tmp2 = explode('||',$tmp);
    $status = $tmp2[1];
    $md5 = $tmp2[2];
    if($status == "good")
    {
        echo " OK<br>";
    }
    else if($status == "expired")
    {
        echo "<font color='red'>your license key has expired, please renew if you want to update, aborting</font><br>";
        footer();
        exit();
    }
    else
    {
        echo "<font color='red'>'<b>".key."</b>' was not valid license key, aborting</font><br>";
        footer();
        exit();
    }
    fclose($fp3);
}

if (defined('version'))
{
    
    if (version<1.99)
    {
        echo "MySQL updates for pre 1.99...<br>";
        @mysql_query("CREATE TABLE ".mysql_prefix."clicks_being_processed ( 
            username char(16) not null, 
            type char(6) not null, 
            amount bigint not null,
            count smallint not null, 
            primary key (username,type) 
            ) TYPE=MyISAM"); 
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
            ) TYPE=MyISAM");
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
            ) TYPE=MyISAM");
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
            ) TYPE=MyISAM");
        
    }
    if (version<2.00)
    {
        echo "MySQL updates for 1.99...<br>";
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
        ) TYPE=MyISAM");
        @mysql_query("create table ".mysql_prefix."chat_users (
        time bigint not null,
        user char(16) not null,
        color int not null,
        room char(16) not null,
        key time(time),
        unique room(room,user)
        ) TYPE=MyISAM");
        @mysql_query('alter table '.mysql_prefix.'user_inbox modify username char(17) not null');
        @mysql_query('alter table '.mysql_prefix.'users add last_email bigint not null');
        @mysql_query('create index last_email on '.mysql_prefix.'users(last_email)');
        @mysql_query('alter table '.mysql_prefix.'chat_users modify color char(6) not null');
        @mysql_query('alter table '.mysql_prefix.'chat_messages modify color char(6) not null');
    }
    
    if (version<2.03){
        echo "MySQL updates for pre 2.03...<br>";
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
    }
    
    if (version<2.04)
    {
        echo "MySQL updates for 2.03...<br>";
        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`) VALUES ('site_offline')");
        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`) VALUES ('site_offline_message')");
        @mysql_query("UPDATE `".mysql_prefix."system_values` SET value = '0' WHERE name = 'site_offline'");    
    }
    
    if (version<2.05)
    {
        echo "MySQL updates for 2.04...<br>";
        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`, `value`) VALUES ('turinglinksize', '100')");
    }

    if (version<2.06)
    {
        echo "MySQL updates for 2.05...<br>";
        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`, `value`) VALUES ('bklocalpath', '../mysql_restore/')");
    }

    if (version<2.07)
    {
        echo "MySQL updates for 2.06...<br>";
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
    
     }

    if (version<2.08)
    {
        echo "MySQL updates for 2.07...<br>";
        @mysql_query("ALTER TABLE `".mysql_prefix."ptc_ads` ADD `cheat_link` TINYINT(1) NOT NULL DEFAULT 0;");
    
    }
    if (version<2.10)
    {
        echo "MySQL updates for 2.09...<br>";
        @mysql_query("CREATE TABLE `".mysql_prefix."visitors_online` (
                            `time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
                            `username` VARCHAR( 50 ) NOT NULL ,
                            `ip` VARCHAR( 50 ) NOT NULL ,
                            `request` VARCHAR( 50 ) NULL ,
                            UNIQUE (`ip`)
                        )");
    
    }
    if (version<2.11)
    {
        echo "MySQL updates for 2.11...<br>";

        @mysql_query("ALTER TABLE `".mysql_prefix."ptc_ads` ADD `creation` TIMESTAMP NOT NULL ;");
        @mysql_query("ALTER TABLE `".mysql_prefix."ptc_ads` ADD `targeting` BLOB NOT NULL ;");

        @mysql_query("ALTER TABLE `".mysql_prefix."rotating_ads` CHANGE `time` `time` TIMESTAMP NOT NULL DEFAULT 0  ;");

        @mysql_query("CREATE TABLE `".mysql_prefix."ptc_targetgroups` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `name` VARCHAR( 50 ) NOT NULL ,
            `targeting` BLOB NOT NULL ,
            PRIMARY KEY ( `id` )
        );");

        @mysql_query("UPDATE ".mysql_prefix."mass_mailer SET time = DATE_SUB(time, INTERVAL 12 HOUR)");
        @mysql_query("UPDATE ".mysql_prefix."last_login SET last_click = last_click + 3600 * -12 WHERE last_click != 0;");
        @mysql_query("UPDATE ".mysql_prefix."last_login SET turing_time = turing_time + 3600 * -12 WHERE turing_time != 0;");
        @mysql_query("UPDATE ".mysql_prefix."cronjobs SET value = value + 3600 * -12 WHERE value > 0;");
        @mysql_query("UPDATE ".mysql_prefix."system_values SET value = value + 3600 * -12 WHERE name = 'cronjobs_ran_at';");

        @mysql_query("ALTER TABLE `".mysql_prefix."visitors_online` ADD `http_referer` VARCHAR( 70 ) NOT NULL ;");
        @mysql_query("ALTER TABLE `".mysql_prefix."ips` ADD `note` VARCHAR( 255 ) NOT NULL ;");
        @mysql_query("ALTER TABLE `".mysql_prefix."users` ADD `upgrade_expires` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';");

        @mysql_query("ALTER TABLE `".mysql_prefix."mass_mailer` CHANGE `time` `time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP "); 
        @mysql_query("ALTER TABLE `".mysql_prefix."email_ads` CHANGE `creation_date` `creation_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ");
        @mysql_query("ALTER TABLE `".mysql_prefix."email_ads` CHANGE `last_sent` `last_sent` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ");
        @mysql_query("ALTER TABLE `".mysql_prefix."redemptions` ADD `upgrade_autoexpire` VARCHAR( 100 ) NOT NULL ;");

        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`, `value`) VALUES ('timezone', '+0.00')");
        @mysql_query("REPLACE INTO `".mysql_prefix."system_values` (`name`, `value`) VALUES ('timeformat', 'm/d/Y H:i')");
            
    }
    if (version<2.18)
    {
        echo "MySQL updates for 2.18...<br>";
        @mysql_query("ALTER TABLE `".mysql_prefix."keywords` ADD `preselected` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `keyword`;");
    }

    if (version<2.19)
    {
        echo "MySQL updates for 2.19...<br>";
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
    }

    if (version<2.20)
    {
        echo "MySQL updates for 2.20...<br>";
        @mysql_query("ALTER TABLE `".mysql_prefix."email_ads` 
                        CHANGE `hrlock` `hrlock` SMALLINT( 4 ) NOT NULL DEFAULT 0
                ");
    }

    if (version<2.21)
    {
        echo "MySQL updates for 2.21...<br>";
        @mysql_query("CREATE TABLE IF NOT EXISTS `".mysql_prefix."abuse` (
            `id` int(11) NOT NULL auto_increment,
            `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
            `username` varchar(255) NOT NULL default '',
            `type` varchar(10) NOT NULL default '',
            `ad` bigint(20) NOT NULL default 0,
            `reason` blob NOT NULL,
            UNIQUE KEY `id` (`id`)
            ) ;");
    }

    if (version<2.22)
    {
        echo "MySQL updates for 2.22...<br>";
        @mysql_query("TRUNCATE `".mysql_prefix."free_refs`");
        @mysql_query("ALTER TABLE  `".mysql_prefix."ptc_ads` ADD  `targeting_mtype` BLOB NOT NULL ;");
        @mysql_query("ALTER TABLE  `".mysql_prefix."ptc_ads` ADD  `turing` TINYINT NOT NULL DEFAULT 0;");
        @mysql_query("ALTER TABLE  `".mysql_prefix."emails` ADD  `comment` BLOB NOT NULL ;");
        @mysql_query("ALTER TABLE  `".mysql_prefix."browsers` ADD  `note` BLOB NOT NULL ;");
    }

    include("version.php");
}
