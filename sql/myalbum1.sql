#
# Table structure for table `myalbum1_cat`
#

CREATE TABLE myalbum1_cat (
  cid    INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  pid    INT(5) UNSIGNED NOT NULL DEFAULT '0',
  title  VARCHAR(50)     NOT NULL DEFAULT '',
  imgurl VARCHAR(150)    NOT NULL DEFAULT '',
  PRIMARY KEY (cid),
  KEY pid (pid)
)
  ENGINE = INNODB;
# --------------------------------------------------------

#
# Table structure for table `myalbum1_photos`
#

CREATE TABLE myalbum1_photos (
  lid       INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  cid       INT(5) UNSIGNED  NOT NULL DEFAULT '0',
  title     VARCHAR(100)     NOT NULL DEFAULT '',
  ext       VARCHAR(10)      NOT NULL DEFAULT '',
  res_x     INT(11)          NOT NULL DEFAULT '0',
  res_y     INT(11)          NOT NULL DEFAULT '0',
  submitter INT(11) UNSIGNED NOT NULL DEFAULT '0',
  status    TINYINT(2)       NOT NULL DEFAULT '0',
  date      INT(10)          NOT NULL DEFAULT '0',
  hits      INT(11) UNSIGNED NOT NULL DEFAULT '0',
  rating    DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
  votes     INT(11) UNSIGNED NOT NULL DEFAULT '0',
  comments  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  tags      VARCHAR(255)     NOT NULL DEFAULT '',
  PRIMARY KEY (lid),
  KEY cid (cid),
  KEY status (status),
  KEY title (title(40))
)
  ENGINE = INNODB;
# --------------------------------------------------------

#
# Table structure for table `myalbum1_text`
#

CREATE TABLE myalbum1_text (
  lid         INT(11) UNSIGNED NOT NULL DEFAULT '0',
  description TEXT             NOT NULL,
  KEY lid (lid)
)
  ENGINE = INNODB;
# --------------------------------------------------------

#
# Table structure for table `myalbum1_votedata`
#

CREATE TABLE myalbum1_votedata (
  ratingid        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  lid             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  ratinguser      INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  rating          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  ratinghostname  VARCHAR(60)         NOT NULL DEFAULT '',
  ratingtimestamp INT(10)             NOT NULL DEFAULT '0',
  PRIMARY KEY (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
)
  ENGINE = INNODB;

