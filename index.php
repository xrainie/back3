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
    print('Спасибо, результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('index.html');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['first_name'])) {
  print('Заполните имя.<br/>');
  $errors = TRUE;
}
if (empty($_POST['email'])) {
  print('Заполните адрес электронной почты.<br/>');
  $errors = TRUE;
}

if (empty($_POST['field-date'])) {
  print('Заполните год.<br/>');
  $errors = TRUE;
}
if (empty($_POST['checkbox'])) {
  print('Ознакомьтесь с контрактом.<br/>');
  $errors = TRUE;
}
if (empty($_POST['biography'])) {
  print('Заполните вашу биографию.<br/>');
  $errors = TRUE;
}


if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

//Сохранение введенных данных в переменные

$first_name = $_POST['first_name'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$limbs = $_POST['limbs'];
$biography = $_POST['biography'];
$date = $_POST['field-date'];

// Сохранение в базу данных.

$user = 'u53712'; // Заменить на ваш логин uXXXXX
$pass = '5427961'; // Заменить на пароль, такой же, как от SSH
$db = new PDO('mysql:host=localhost;dbname=u53712', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

// Подготовленный запрос. Не именованные метки.

try {
  $stmt = $db->prepare("INSERT INTO application (name, email, year, gender, limb, biography) VALUES (:name, :email, :year, :gender, :limbs, :biography)");
  $stmt->bindParam(':name', $first_name);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':year', $date);
  $stmt->bindParam(':gender', $gender);
  $stmt->bindParam(':limbs', $limbs);
  $stmt->bindParam(':biography', $biography);

  $name = $_POST['first_name'];
  $email = $_POST['email'];
  $year = $_POST['field-date'];
  $gender = $_POST['gender'][0];
  $limbs = $_POST['limbs'][0];
  $biography = $_POST['biography'];

  $stmt->execute();
  $dbh = new PDO('mysql:host=localhost;dbname=u53712', $user, $pass);
  $last_id = $db->lastInsertId();

  $stmt = $db->prepare("INSERT INTO abilities (id, $abil) VALUES (:id, 1)");
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

/*
$query = "INSERT INTO application (name, email, year, gender, limb, biography) VALUES ('$first_name', '$email', '$date', '$gender', '$limbs', '$biography')";

$result = mysqli_query($db, $query)
or die ("Ошибка выполнения запроса к БД");
*/
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
