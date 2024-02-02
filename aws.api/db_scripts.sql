
ALTER TABLE `region` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `app_district` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `app_sub_county` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `app_parish` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `app_village` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `app_project` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `app_organisation` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `chart` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 


ALTER TABLE `user` ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `admin_user` ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 


ALTER TABLE `question` ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `question` DROP `date_created`;


-- ALTER TABLE `question` ADD `form_id` INT NULL DEFAULT NULL AFTER `question_id`; 
ALTER TABLE `question_form` ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `active`, ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`; 
ALTER TABLE `question_form` ADD `renamed` JSON NULL AFTER `title_fields`; 
ALTER TABLE `question_form` DROP FOREIGN KEY `question_form_ibfk_1`;
ALTER TABLE `question_form` DROP `category_id`;
ALTER TABLE `question_form` ADD `conditional_logic` JSON NULL AFTER `renamed`; 
ALTER TABLE `question_form` ADD `followup_interval` INT NULL AFTER `followup_prefill`;


CREATE TABLE `indicator_chart` ( `chart_id` INT NOT NULL AUTO_INCREMENT ,  `title` VARCHAR(100) NOT NULL ,  `form_id` INT NOT NULL ,  `question_id` INT NOT NULL ,  `target` INT NULL ,  `start_date` DATE NOT NULL ,  `end_date` DATE NOT NULL ,  `created_at` DATETIME NOT NULL ,  `updated_at` DATETIME NOT NULL ,  `deleted_at` DATETIME NULL ,    PRIMARY KEY  (`chart_id`)) ENGINE = InnoDB;







--
-- Table structure for table `question_library`
--

CREATE TABLE `question_library` (
  `question_id` int NOT NULL,
  `question` varchar(200) NOT NULL,
  `answer_type_id` int NOT NULL,
  `answer_values` json DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `question_library`
--
ALTER TABLE `question_library`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `answer_type_id` (`answer_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `question_library`
--
ALTER TABLE `question_library`
  MODIFY `question_id` int NOT NULL AUTO_INCREMENT;
COMMIT;



CREATE OR REPLACE VIEW view_question_library AS
SELECT

X.question_id,
X.question,
X.answer_type_id,
A.machine_name AS answer_type,
X.answer_values,
X.active,
X.created_at,
X.updated_at,
X.deleted_at

FROM question_library X
LEFT JOIN answer_type A ON A.answer_type_id = X.answer_type_id;




CREATE OR REPLACE VIEW view_question AS
SELECT

X.question_id,
X.form_id,
X.question,
X.answer_type_id,
A.machine_name AS answer_type,
X.answer_values,
-- X.is_conditional,
-- X.question_condition,
X.active,
X.created_at,
X.updated_at,
X.deleted_at

FROM question X
LEFT JOIN answer_type A ON A.answer_type_id = X.answer_type_id;


