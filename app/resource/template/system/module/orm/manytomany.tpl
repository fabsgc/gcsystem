CREATE TABLE IF NOT EXISTS `{$table}` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`{$entity1}_{$field1}` int(11) NOT NULL,
	`{$entity2}_{$field2}` int(11) NOT NULL,
	`count` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	FOREIGN KEY ({$entity1}_{$field1}) REFERENCES {$entity1}({$field1}),
	FOREIGN KEY ({$entity2}_{$field2}) REFERENCES {$entity2}({$field2})
);