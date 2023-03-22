<?php
require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");

Mustache_Autoloader::register();
$m = new Mustache_Engine;

function studentDashboardTutor(Database $base, int $id) {
    global $m;
    return($m->render(file_get_contents('view/templates-mustache/dashboard-table-student.mustache'), ["person" => $base->executeQuery("SELECT A.id_student,A.first_name,A.last_name,B.name AS center,A.name AS promotion FROM (SELECT Student.id_group,id_tutor,name,id_student,first_name,last_name,id_center FROM is_assigned_to JOIN Year_group ON is_assigned_to.id_group=Year_group.id_group JOIN Student ON Year_group.id_group=Student.id_group) AS A JOIN (SELECT id_tutor,first_name,last_name, Center.id_center, Center.name FROM Tutor JOIN Center ON Tutor.id_center=Center.id_center) AS B ON A.id_tutor=B.id_tutor AND A.id_center=B.id_center WHERE B.id_tutor=:id",["id" => $id])]));
}

function studentDashboardAdmin(Database $base) {
    global $m;
    return($m->render(file_get_contents('view/templates-mustache/dashboard-table-student.mustache'), ["person" => $base->executeQuery("SELECT id_student,first_name,last_name,Center.name AS center,Year_group.name AS promotion FROM Student JOIN Center ON Student.id_center=Center.id_center JOIN Year_group ON Student.id_group=Year_group.id_group")]));
}