<link rel="stylesheet" href="style.css"></link>
<form action="" method="POST">
  <p><input type="text" name="fio" placeholder="Ваше имя"></p>

  <p><input type="email" name="email" placeholder="Ваша почта"></p>
  <br>
  <h2>Ваш год рождения</h2>
  <select name="year">
    <?php 
    for ($i = 1900; $i <= 2023; $i++) {
      printf('<option value="%d">%d Год</option>', $i, $i);
    }
    ?>
  </select>
  <br>
  <br>
  <h2>Ваш пол</h2>
  <p><input type="radio" name="r1[]" value="male"> Мужской</p>
  <p><input type="radio" name="r1[]" value="female"> Женский</p>
  <p><input type="radio" name="r1[]" value="other">Другой</p>

  <br> 
  <h2>Количество ваших конечностей</h2>
  <p><input type="radio" name="r2[]" value="2">2</p>
  <p><input type="radio" name="r2[]" value="3">3</p>
  <p><input type="radio" name="r2[]" value="4">4</p>
  <p><input type="radio" name="r2[]" value="many">4+</p>
  <br> 
  <h2>Ваши сверхспособности</h2>
  <p><select multiple="multiple" name="abilities[]">
    <option value="Immortality">Бессмертие</option>
    <option value="Passing through walls">Прохождение сквозь стены</option>
    <option value="Levitation">Левитация</option>
    </select>
  </p>
  <br>
  <h2>Ваша биография</h2>
  <p><textarea name="biography"></textarea></p>
  <br>
  <h2>С контрактом ознакомлен(а)</h2>
  <p><input type="checkbox" name="cb"></p>
  <input type="submit" value="Подтверждаю" />
</form>