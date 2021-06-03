<?

require_once 'User.php';
require_once 'GenPassword.php';

$user = new User('Igor', 'Politaev', 25, 'mail@mail.ru', 20);

echo 'User name: ' . $user->getName() . ' ' . $user->getSurname() . '<br>';
echo 'Age: ' . $user->getAge() . '<br>';
echo 'Email: ' . $user->getEmail() . '<br>';
echo 'Password: ' . (new GenPassword(10))->getPass() . '<br>';

?>