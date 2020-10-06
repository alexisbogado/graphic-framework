# Graphic Framework
-----------

A light-weight PHP MVC framework
- Writing README...

# Installation
-----------
1. Execute this following query
```sql
CREATE TABLE `roles` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', NOW(), NULL),
(2, 'User', NOW(), NULL);

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `roles_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users` ADD CONSTRAINT `roles_relationship` FOREIGN KEY (`roles_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
```

# TO-DO List
------------
[ ] WRITE README
[ ] External libraries implementation
[ ] Rewrite Database/Model
[ ] Add functions @if, @foreach, @for and so to views
[ ] Make migrations system (?)