CREATE TABLE `students` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `email` varchar(255),
  `password` varchar(255),
  `number` varchar(255),
  `is_verified` boolean,
  `created_at` timestamp,
  `university_id` int NOT NULL
);

CREATE TABLE `login_activity` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `login_time` timestamp NOT NULL
);

CREATE TABLE `university` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255)
);

CREATE TABLE `department` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `university_id` int NOT NULL,
  `name` varchar(255)
);

CREATE TABLE `subject` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `department_id` int NOT NULL,
  `name` varchar(255)
);

CREATE TABLE `inquiry` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `message` varchar(255),
  `created_at` timestamp
);

CREATE TABLE `input` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255)
);

CREATE TABLE `output` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255)
);

CREATE TABLE `practical` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `youtube_link` varchar(255),
  `created_at` timestamp,
  `subject_id` int NOT NULL
);

CREATE TABLE `practical_input` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `input_id` int,
  `practical_id` int
);

CREATE TABLE `practical_output` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `output_id` int,
  `practical_id` int
);

CREATE TABLE `practical_input_values` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `practical_input_id` int,
  `serial_num` int,
  `value` varchar(255)
);

CREATE TABLE `practical_output_values` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `practical_output_id` int,
  `serial_num` int,
  `value` varchar(255)
);

ALTER TABLE `students` ADD FOREIGN KEY (`university_id`) REFERENCES `university` (`id`);

ALTER TABLE `login_activity` ADD FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

ALTER TABLE `department` ADD FOREIGN KEY (`university_id`) REFERENCES `university` (`id`);

ALTER TABLE `subject` ADD FOREIGN KEY (`department_id`) REFERENCES `department` (`id`);

ALTER TABLE `inquiry` ADD FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

ALTER TABLE `practical` ADD FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`);

ALTER TABLE `practical_input` ADD FOREIGN KEY (`input_id`) REFERENCES `input` (`id`);

ALTER TABLE `practical_input` ADD FOREIGN KEY (`practical_id`) REFERENCES `practical` (`id`);

ALTER TABLE `practical_output` ADD FOREIGN KEY (`output_id`) REFERENCES `output` (`id`);

ALTER TABLE `practical_output` ADD FOREIGN KEY (`practical_id`) REFERENCES `practical` (`id`);

ALTER TABLE `practical_input_values` ADD FOREIGN KEY (`practical_input_id`) REFERENCES `practical_input` (`id`);

ALTER TABLE `practical_output_values` ADD FOREIGN KEY (`practical_output_id`) REFERENCES `practical_output` (`id`);
