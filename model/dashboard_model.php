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

function companyDashboard(Database $base) {
    global $m;
    return($m->render(file_get_contents('view/templates-mustache/dashboard-table-business.mustache'), ["company" => $base->executeQuery("SELECT Company.id_company,Company.name AS company_name,Company.cesi_interns,Company.email,Field.name AS field_name FROM Company JOIN Field ON Company.id_field=Field.id_field WHERE Company.visible=1")]));
}

function internshipDashboard(Database $base) {
    global $m;
    return($m->render(file_get_contents('view/templates-mustache/dashboard-table-internship.mustache'), ["internship" => $base->executeQuery("SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,City.name as city, postcode FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1")]));
}

function tutorDashboard(Database $base) {
    global $m;
    return($m->render(file_get_contents('view/templates-mustache/dashboard-table-tutor.mustache'), ["person" => $base->executeQuery("SELECT id_tutor,first_name,last_name,name AS center FROM Tutor JOIN Center ON Center.id_center=Tutor.id_center")]));
}