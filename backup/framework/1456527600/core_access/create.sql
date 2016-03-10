CREATE TABLE `core_access` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT COMMENT 'Access Id',
  `node` varchar(255) DEFAULT NULL COMMENT 'Node Name',
  `core_profile_id` varchar(255) DEFAULT NULL COMMENT 'Profile Code',
  `action` varchar(255) DEFAULT NULL COMMENT 'Action Code',
  `createdby` int(11) DEFAULT NULL COMMENT 'Created User Id',
  `createdat` datetime DEFAULT NULL COMMENT 'Created Datetime',
  `updatedby` int(11) DEFAULT NULL COMMENT 'Updated User Id',
  `updatedat` datetime DEFAULT NULL COMMENT 'Updated Datetime',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Access Details'