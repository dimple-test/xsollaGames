CREATE TABLE `xsolla_games`.`games` (
  `id` INT NOT NULL,
  `title` VARCHAR(45) NULL,
  `platform` VARCHAR(45) NULL,
  `star_rating` INT NULL,
  `review` VARCHAR(200) NULL,
  `last_played` TIMESTAMP NULL,
  `created` TIMESTAMP NULL,
  `updated` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE);


INSERT INTO `xsolla_games`.`games` (`id`, `title`, `platform`, `star_rating`, `review`, `last_played`, `created`, `updated`) VALUES ('1', 'pubg', 'php', '1', 'good game', '2013-07-04 05:05:10', '2013-07-04 05:05:10', '2013-07-04 05:05:10');
