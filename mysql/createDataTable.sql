CREATE TABLE `et_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_a` varchar(30) DEFAULT '0',
  `c_b` varchar(30) DEFAULT '0',
  `c_c` varchar(30) DEFAULT '0',
  `c_d` varchar(30) DEFAULT '0',
  `c_e` varchar(30) DEFAULT '0',
  `c_f` varchar(30) DEFAULT '0',
  `c_g` varchar(30) DEFAULT '0',
  `c_h` varchar(30) DEFAULT '0',
  `c_i` varchar(30) DEFAULT '0',
  `c_j` varchar(30) DEFAULT '0',
  `c_k` varchar(30) DEFAULT '0',
  `c_l` varchar(30) DEFAULT '0',
  `c_m` varchar(30) DEFAULT '0',
  `c_n` varchar(30) DEFAULT '0',
  PRIMARY KEY (`id`)
)


CREATE TABLE `ft_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_a` varchar(30) DEFAULT '0',
  `c_b` varchar(30) DEFAULT '0',
  `c_c` varchar(30) DEFAULT '0',
  `c_d` varchar(30) DEFAULT '0',
  `c_e` varchar(30) DEFAULT '0',
  `c_f` varchar(30) DEFAULT '0',
  `c_g` varchar(30) DEFAULT '0',
  `c_h` varchar(30) DEFAULT '0',
  `c_i` varchar(30) DEFAULT '0',
  `c_j` varchar(30) DEFAULT '0',
  `c_k` varchar(30) DEFAULT '0',
  `c_l` varchar(30) DEFAULT '0',
  `c_m` varchar(30) DEFAULT '0',
  `c_n` varchar(30) DEFAULT '0',
  PRIMARY KEY (`id`)
)


CREATE TABLE `smdProductData` (
  `SN` int(11) NOT NULL AUTO_INCREMENT,
  `insertTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastModifyTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data_0` varchar(30) NOT NULL COMMENT 'model',
  `data_1` varchar(30) NOT NULL COMMENT 'planProductNum',
  `data_2` varchar(30) DEFAULT '0',
  `data_3` varchar(30) DEFAULT '0',
  `data_4` varchar(30) DEFAULT '0',
  `data_5` varchar(30) DEFAULT '0',
  `data_6` varchar(30) DEFAULT '0',
  `data_7` varchar(30) DEFAULT '0',
  `data_8` varchar(30) DEFAULT '0',
  `data_9` varchar(30) DEFAULT '0',
  `data_10` varchar(30) DEFAULT '0',
  `data_11` varchar(30) DEFAULT '0',
  `data_12` varchar(30) DEFAULT '0',
  `data_13` varchar(30) DEFAULT '0',
  `timeFlag` char(10) NOT NULL,
  `date` char(15) NOT NULL,
  `dataFlag` char(10) DEFAULT 'normal',
  PRIMARY KEY (`SN`)
)
