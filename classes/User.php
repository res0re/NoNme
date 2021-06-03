<?
class User {
   private $name;
   private $surname;
   private $age;
   private $email;
   private $password;

   public function __construct($name, $surname, $age, $email, $lenpass) 
   {
      $this->name = $name;
      $this->surname = $surname;
      $this->age = $age;
      $this->email = $email;
      $this->password = (new GenPassword($lenpass))->getPass();
   }

   public function getSurname()
   {
      return $this->surname;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getAge()
   {
      return $this->age;
   }

   public function getEmail()
   {
      return $this->email;
   }

   public function getPass()
   {
      return $this->password;
   }
}
?>