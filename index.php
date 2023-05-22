<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['fio'])) {
  print('Нет данных: Имя.<br/>');
  $errors = TRUE;
}

if (empty($_POST['email'])) {
  print('Нет данных: Почта.<br/>');
  $errors = TRUE;
}

if (empty($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
  print('Нет данных: Год рождения.<br/>');
  $errors = TRUE;
}

if(empty($_POST['biography'])) {
  print('Нет данных: Биография.<br/>');
  $errors = TRUE;
}

if(!isset($_POST['r1'])){
  print('Нет данных: Пол.<br/>');
  $errors = TRUE;
}

if(!isset($_POST['abilities'])){
  print('Нет данных: Способности.<br/>');
  $errors = TRUE;
}

if(!isset($_POST['r2'])){
  print('Нет данных: Количество конечностей.<br/>');
  $errors = TRUE;
}

if(!isset($_POST['cb'])){
  print('Отсутствует согласие с контрактом.<br/>');
  $errors = TRUE;
}



// *************
// Тут необходимо проверить правильность заполнения всех остальных полей.
// *************

if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.

$user = 'u53712'; // Заменить на ваш логин uXXXXX
$pass = '5427961'; // Заменить на пароль, такой же, как от SSH
$db = new PDO('mysql:host=localhost;dbname=u52841', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

// Подготовленный запрос. Не именованные метки.

try {
  $stmt = $db->prepare("INSERT INTO application (name, email, year, gender, limb, biography) VALUES (:name, :email, :year, :gender, :limbs, :biography)");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':year', $year);
  $stmt->bindParam(':gender', $gender);
  $stmt->bindParam(':limbs', $limbs);
  $stmt->bindParam(':biography', $biography);

  $name = $_POST['fio'];
  $email = $_POST['email'];
  $year = $_POST['year'];
  $gender = $_POST['r1'][0];
  $limbs = $_POST['r2'][0];
  $biography = $_POST['biography'];

  $stmt->execute();
  $dbh = new PDO('mysql:host=localhost;dbname=u53712', $user, $pass);
  $last_id = $db->lastInsertId();

  $stmt = $db->prepare("INSERT INTO abilities (id, ability) VALUES (:id, :ability)");
  
  foreach($_POST['abilities'] as $abil) {
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':ability', $ability);
    $id = $last_id;
    $ability = $abil;
    
    $stmt->execute();
  }

}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}
//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(['label'=>'perfect', 'color'=>'green']);
 
//Еще вариант
/*$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');