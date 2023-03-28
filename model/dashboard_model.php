<?php
require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");

class Dashboard extends Database{
    private $m;
    private $page;
    private $per_page;
    private $table;
    private $total;
    private $template_location;
    public function __construct(int $page, int $per_page) {
        $this->page = $page;
        $this->per_page = $per_page;
        parent::__construct();
        Mustache_Autoloader::register();
        $this->m = new Mustache_Engine;
    }
    function studentDashboardTutor(int $id) {
        $this->table = $this->executeQuery("SELECT A.id_student,A.first_name,A.last_name,B.name AS center,A.name AS promotion FROM (SELECT Student.id_group,id_tutor,name,id_student,first_name,last_name,id_center FROM is_assigned_to JOIN Year_group ON is_assigned_to.id_group=Year_group.id_group JOIN Student ON Year_group.id_group=Student.id_group) AS A JOIN (SELECT id_tutor,first_name,last_name, Center.id_center, Center.name FROM Tutor JOIN Center ON Tutor.id_center=Center.id_center) AS B ON A.id_tutor=B.id_tutor AND A.id_center=B.id_center WHERE B.id_tutor=:id LIMIT :end OFFSET :beginning",["id" => $id, "end" => $this->per_page, "beginning" => ($this->per_page*($this->page-1))]);
        $this->total = ceil($this->executeQuery("SELECT COUNT(A.id_student) FROM (SELECT Student.id_group,id_tutor,name,id_student,first_name,last_name,id_center FROM is_assigned_to JOIN Year_group ON is_assigned_to.id_group=Year_group.id_group JOIN Student ON Year_group.id_group=Student.id_group) AS A JOIN (SELECT id_tutor,first_name,last_name, Center.id_center, Center.name FROM Tutor JOIN Center ON Tutor.id_center=Center.id_center) AS B ON A.id_tutor=B.id_tutor AND A.id_center=B.id_center WHERE B.id_tutor=:id",["id" => $id], return_option:PDO::FETCH_NUM)[0][0]/$this->per_page);
        $this->template_location = "view/templates-mustache/dashboard-table-student.mustache";
    }
    
    function studentDashboardAdmin() {
        $this->table = $this->executeQuery("SELECT id_student,first_name,last_name,Center.name AS center,Year_group.name AS promotion FROM Student JOIN Center ON Student.id_center=Center.id_center JOIN Year_group ON Student.id_group=Year_group.id_group LIMIT :end OFFSET :beginning", ["end" => $this->per_page, "beginning" => ($this->per_page*($this->page-1))]);
        $this->total = ceil($this->executeQuery("SELECT COUNT(id_student) FROM Student JOIN Center ON Student.id_center=Center.id_center JOIN Year_group ON Student.id_group=Year_group.id_group", return_option:PDO::FETCH_NUM)[0][0]/$this->per_page);
        $this->template_location = "view/templates-mustache/dashboard-table-student.mustache";
    }
    
    function companyDashboard() {
        $this->table = $this->executeQuery("SELECT Company.id_company,Company.name AS company_name,Company.cesi_interns,Company.email,Field.name AS field_name FROM Company JOIN Field ON Company.id_field=Field.id_field WHERE Company.visible=1 LIMIT :end OFFSET :beginning", ["end" => $this->per_page, "beginning" => ($this->per_page*($this->page-1))]);
        $this->total = ceil($this->executeQuery("SELECT COUNT(Company.id_company) FROM Company JOIN Field ON Company.id_field=Field.id_field WHERE Company.visible=1", return_option:PDO::FETCH_NUM)[0][0]/$this->per_page);
        $this->template_location = "view/templates-mustache/dashboard-table-business.mustache";
    }
    
    function internshipDashboard() {
        $this->table = $this->executeQuery("SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,City.name as city, postcode FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1 LIMIT :end OFFSET :beginning", ["end" => $this->per_page, "beginning" => ($this->per_page*($this->page-1))]);
        $this->total = ceil($this->executeQuery("SELECT COUNT(Internship_offer.id_offer) FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1", return_option:PDO::FETCH_NUM)[0][0]/$this->per_page);
        $this->template_location = "view/templates-mustache/dashboard-table-internship.mustache";
    }
    
    function tutorDashboard() {
        $this->table = $this->executeQuery("SELECT id_tutor,first_name,last_name,name AS center FROM Tutor JOIN Center ON Center.id_center=Tutor.id_center LIMIT :end OFFSET :beginning", ["end" => $this->per_page, "beginning" => ($this->per_page*($this->page-1))]);
        $this->total = ceil($this->executeQuery("SELECT COUNT(id_tutor) FROM Tutor JOIN Center ON Center.id_center=Tutor.id_center", return_option:PDO::FETCH_NUM)[0][0]/$this->per_page);
        $this->template_location = "view/templates-mustache/dashboard-table-tutor.mustache";
    }
    
    function applyTemplate() {
        if(($this->page+1) > $this->total) {
            $next=$this->page;
        }
        else {
            $next=$this->page+1;
        }
        return($this->m->render(file_get_contents($this->template_location), ["iteration" => $this->table, "previous_page" => $this->page-1, "next_page" => $next, "page" => $this->page, "total_page" => $this->total]));
    }
}

function recherche();
{
    $recherche =$_GET['q']
    $userpage=$_GET['userpage'];
    $bdd = debConnect();
    $req = $bdd->prepare(query: SELECT first_name, last_name, id_center, id_group, login FROM Students WHERE (first_name LIKE "%' . $recherche . '%" 
    OR last_name LIKE "%' . $recherche . '%" 
    OR id_center LIKE "%' . $recherche . '%" 
    OR id_group LIKE "%' . $recherche . '%" 
   OR login LIKE "%' . $recherche . '%")
$req->execute();
return $req->fecthAll();
}
