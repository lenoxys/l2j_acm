ALTER TABLE `accounts` ADD `email` VARCHAR( 255 ) NULL DEFAULT NULL ;

CREATE TABLE IF NOT EXISTS `account_data` (
  account_name VARCHAR(45) NOT NULL DEFAULT '',
  var  VARCHAR(20) NOT NULL DEFAULT '',
  value VARCHAR(255) ,
  PRIMARY KEY  (account_name,var)
);

#cleaning account

ALTER TABLE `accounts` ADD `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;
UPDATE `l2jdb`.`accounts` SET `created_time` = NOW( );