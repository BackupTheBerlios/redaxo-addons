<?php

// ?!?! nur installieren wenn addon von simple_user vorhanden
// TODOS: tabellen vorhanden ? nur dann install ok ..


// CREATE/UPDATE DATABASE

$ins = new sql;
$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_article_comment (
  id int(11) NOT NULL auto_increment,
  user_id varchar(255) NOT NULL default '',
  user_email varchar(255) NOT NULL default '',
  user_registered int(1) NOT NULL default '0',
  article_id int(11) NOT NULL default '0',
  comment text NOT NULL,
  stamp int(11) NOT NULL default '0',
  status int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM
");


$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_board (
  message_id int(11) NOT NULL auto_increment,
  re_message_id int(11) NOT NULL default '0',
  board_id varchar(255) NOT NULL default '',
  user_id varchar(255) NOT NULL default '',
  user_email varchar(255) NOT NULL default '',
  user_registered tinyint(1) NOT NULL default '0',
  replies int(11) NOT NULL default '0',
  last_entry varchar(255) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  message text NOT NULL,
  stamp int(11) NOT NULL default '0',
  status int(1) NOT NULL default '0',
  PRIMARY KEY  (message_id)
) TYPE=MyISAM
");


$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_ff_file (
  file_id int(14) NOT NULL auto_increment,
  re_file_id int(14) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  description text NOT NULL,
  file_name varchar(255) NOT NULL default '',
  file_type varchar(255) NOT NULL default '',
  file_size int(14) NOT NULL default '0',
  folder_id int(14) NOT NULL default '0',
  folder_name varchar(255) NOT NULL default '',
  project_id int(14) NOT NULL default '0',
  stamp int(14) NOT NULL default '0',
  date varchar(14) NOT NULL default '',
  user_id int(14) NOT NULL default '0',
  version int(14) NOT NULL default '1',
  deleted tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (file_id),
  UNIQUE KEY id (file_id)
) TYPE=MyISAM
");


$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_ff_folder (
  folder_id int(14) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  re_folder_id int(14) NOT NULL default '0',
  user_id int(14) NOT NULL default '0',
  project_id int(14) NOT NULL default '0',
  status tinyint(4) NOT NULL default '0',
  modus tinyint(4) NOT NULL default '0',
  prior char(2) NOT NULL default '',
  folder_name varchar(255) NOT NULL default '',
  PRIMARY KEY  (folder_id),
  UNIQUE KEY id (folder_id),
  KEY folder_id (folder_id,re_folder_id,user_id,project_id,status,modus,prior)
) TYPE=MyISAM
");


$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_group (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  extras text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM
");


$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_session (
  session varchar(255) NOT NULL default '',
  user_id varchar(255) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  stamp varchar(255) NOT NULL default '',
  PRIMARY KEY  (session)
) TYPE=MyISAM
");


$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_u_g (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM
");


$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_user (
  id int(11) NOT NULL auto_increment,
  user_login varchar(255) NOT NULL default '',
  user_password varchar(255) NOT NULL default '',
  user_name varchar(255) NOT NULL default '',
  user_firstname varchar(255) NOT NULL default '',
  user_gender char(1) NOT NULL default '',
  user_birthdate varchar(255) NOT NULL default '',
  user_eyecolor varchar(255) NOT NULL default '',
  user_haircolor varchar(255) NOT NULL default '',
  user_street varchar(255) NOT NULL default '',
  user_plz varchar(255) NOT NULL default '',
  user_town varchar(255) NOT NULL default '',
  user_phone varchar(255) NOT NULL default '',
  user_mobile varchar(255) NOT NULL default '',
  user_email varchar(255) NOT NULL default '',
  user_icq varchar(255) NOT NULL default '',
  user_aim varchar(255) NOT NULL default '',
  user_msn varchar(255) NOT NULL default '',
  user_skype varchar(255) NOT NULL default '',
  user_private_data_public int(11) NOT NULL default '0',
  company_name varchar(255) NOT NULL default '',
  company_department varchar(255) NOT NULL default '',
  company_operating_field varchar(255) NOT NULL default '',
  company_street varchar(255) NOT NULL default '',
  company_plz varchar(255) NOT NULL default '',
  company_town varchar(255) NOT NULL default '',
  company_phone varchar(255) NOT NULL default '',
  company_mobile varchar(255) NOT NULL default '',
  company_email varchar(255) NOT NULL default '',
  company_data_public int(11) NOT NULL default '0',
  personally_positive_characteristics text NOT NULL,
  personally_negative_characteristics text NOT NULL,
  personally_hobby text NOT NULL,
  personally_favorite_place text NOT NULL,
  personally_slogan text NOT NULL,
  personally_data_public int(11) NOT NULL default '0',
  info_newsletter int(11) NOT NULL default '0',
  info_mail int(11) NOT NULL default '0',
  user_status int(11) NOT NULL default '0',
  user_typ int(11) NOT NULL default '0',
  user_file1 varchar(255) NOT NULL default '',
  user_file2 varchar(255) NOT NULL default '',
  personally_job text NOT NULL,
  personally_homepage text NOT NULL,
  personally_reason text NOT NULL,
  personally_figure text NOT NULL,
  personally_singlestatus text NOT NULL,
  login_activation int(11) NOT NULL default '0',
  activation_key varchar(255) NOT NULL default '',
  mail_nlid varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM
");


$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_user_comment (
  id int(11) NOT NULL auto_increment,
  user_id varchar(255) NOT NULL default '',
  from_user_email varchar(255) NOT NULL default '',
  from_user_registered tinyint(4) NOT NULL default '0',
  from_user_id varchar(255) NOT NULL default '',
  message text NOT NULL,
  stamp int(11) NOT NULL default '0',
  status int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM
");


$ins->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."5_user_mail (
  id int(11) NOT NULL auto_increment,
  user_id varchar(255) NOT NULL default '',
  from_user_id varchar(255) NOT NULL default '',
  message text NOT NULL,
  stamp int(11) NOT NULL default '0',
  status int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM
");


$REX['ADDON']['install']["community"] = 1;
// ERRMSG IN CASE: $REX[ADDON][installmsg]["import_export"] = "Leider konnte nichts installiert werden da.";


?>