<?php

// try to create PDO object named $DB with host=localhost and connect it with database "hotel" using username root and empty password
// PDO stands for PHP Data Objects, it is an interface for accessing databases regardless of which database is used.
// why I used PDO rather than mysqli ? answer: https://websitebeaver.com/php-pdo-vs-mysqli

try {
    $DB = new PDO('mysql:host=localhost;dbname=hotel', 'root', '');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
