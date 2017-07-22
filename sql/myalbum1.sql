#
# Table structure for table `myalbum1_cat`
#

CREATE TABLE myalbum1_cat (
  cid int(5) unsigned NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  imgurl varchar(150) NOT NULL default '',
  PRIMARY KEY  (cid),
  KEY pid (pid)
) ENGINE=INNODB;
# --------------------------------------------------------

#
# Table structure for table `myalbum1_photos`
#

CREATE TABLE myalbum1_photos (
  lid int(11) unsigned NOT NULL auto_increment,
  cid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  ext varchar(10) NOT NULL default '',
  res_x int(11) NOT NULL default '0',
  res_y int(11) NOT NULL default '0',
  submitter int(11) unsigned NOT NULL default '0',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  hits int(11) unsigned NOT NULL default '0',
  rating double(6,4) NOT NULL default '0.0000',
  votes int(11) unsigned NOT NULL default '0',
  comments int(11) unsigned NOT NULL default '0',
  tags varchar(255) NOT NULL default '',
  PRIMARY KEY  (lid),
  KEY cid (cid),
  KEY status (status),
  KEY title (title(40))
) ENGINE=INNODB;
# --------------------------------------------------------

#
# Table structure for table `myalbum1_text`
#

CREATE TABLE myalbum1_text (
  lid int(11) unsigned NOT NULL default '0',
  description text NOT NULL,
  KEY lid (lid)
) ENGINE=INNODB;
# --------------------------------------------------------

#
# Table structure for table `myalbum1_votedata`
#

CREATE TABLE myalbum1_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) unsigned NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
) ENGINE=INNODB;

