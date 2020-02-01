  SELECT
    `u`.`id`        AS `user_id`,
    `u`.`name`      AS `user_name`,
    `u`.`email`     AS `user_email`,
    `u`.`venue_id`  AS `user_venue_id`,
    `g`.`id`        AS `group_id`,
    `g`.`gname`     AS `group_name`,
    `g`.`descp`     AS `group_descp`,
    `v`.`venue_id`  AS `venue_id`,
    `v`.`city_id`   AS `city_id`,
    `v`.`vname`     AS `venue_fa_name`,
    `v`.`status`    AS `venue_status`,
    `v`.`city_name` AS `venue_en_name`
  FROM ((`assets`.`users` `u` LEFT JOIN
    (`assets`.`groups` `g` LEFT JOIN `assets`.`group_user` `gu` ON (`gu`.`group_id` = `g`.`id`))
      ON (`gu`.`user_id` = `u`.`id`)) LEFT JOIN `assets`.`venue_view` `v` ON (`u`.`venue_id` = `v`.`venue_id`));

