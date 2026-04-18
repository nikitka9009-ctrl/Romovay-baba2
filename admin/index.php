<?php
session_start();
define('ADMIN_PASSWORD', password_hash('RomovayaBaba2026!', PASSWORD_DEFAULT));

if (isset($_POST['login'])) {
    if (password_verify($_POST['password'], ADMIN_PASSWORD)) {
        $_SESSION['admin'] = true;
    } else {
        $error = 'Неверный пароль';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php'); exit;
}

if (!isset($_SESSION['admin'])) {
    echo '<form method="post" style="max-width:300px;margin:100px auto;font-family:sans-serif;padding:20px;border:1px solid #ccc;border-radius:8px;">
            <h2>Вход в админку</h2>
            <input type="password" name="password" placeholder="Пароль" required style="width:100%;padding:8px;margin:10px 0;">
            <button type="submit" name="login" style="width:100%;padding:10px;background:#A0522D;color:#fff;border:none;border-radius:4px;cursor:pointer;">Войти</button>
            <p style="color:red">'.($error??'').'</p>
          </form>';
    exit;
}

$products = json_decode(file_get_contents('../data/products.json'), true);
$reviews = json_decode(file_get_contents('../data/reviews.json'), true);
?>
<!DOCTYPE html>
<html lang="ru"><head><meta charset="UTF-8"><title>Админка | Ромовая баба</title>
<style>body{font-family:system-ui;max-width:1000px;margin:20px auto;padding:0 20px} table{width:100%;border-collapse:collapse;margin:20px 0} th,td{padding:8px;border:1px solid #ddd;text-align:left} input,textarea,select{padding:6px;width:100%;box-sizing:border-box} .btn{background:#A0522D;color:#fff;padding:6px 12px;border:none;border-radius:4px;cursor:pointer} .btn:hover{background:#5D2E12}</style></head><body>
<h1>Управление контентом <a href="?logout" style="float:right;font-size:14px;color:#666">Выйти</a></h1>

<h2>📦 Товары</h2>
<form action="api.php" method="post">
  <table>
    <tr><th>ID</th><th>Категория</th><th>Название</th><th>Описание</th><th>Цена</th><th>Фото (имя файла)</th><th>Действие</th></tr>
    <?php foreach($products as $i=>$p): ?>
    <tr>
      <td><input type="hidden" name="products[<?php echo $i?>][id]" value="<?php echo $p['id']?>"><?php echo $p['id']?></td>
      <td><select name="products[<?php echo $i?>][category]">
        <option value="pastry" <?php if($p['category']=='pastry') echo 'selected';?>>Выпечка</option>
        <option value="pies" <?php if($p['category']=='pies') echo 'selected';?>>Пироги</option>
        <option value="our-menu" <?php if($p['category']=='our-menu') echo 'selected';?>>Наше меню</option>
      </select></td>
      <td><input type="text" name="products[<?php echo $i?>][name]" value="<?php echo htmlspecialchars($p['name'])?>"></td>
      <td><input type="text" name="products[<?php echo $i?>][desc]" value="<?php echo htmlspecialchars($p['desc'])?>"></td>
      <td><input type="text" name="products[<?php echo $i?>][price]" value="<?php echo htmlspecialchars($p['price'])?>"></td>
      <td><input type="text" name="products[<?php echo $i?>][img]" value="<?php echo htmlspecialchars($p['img'])?>"></td>
      <td><button class="btn" type="submit" name="action" value="save_products">Сохранить</button></td>
    </tr>
    <?php endforeach; ?>
  </table>
</form>

<h2>💬 Отзывы</h2>
<form action="api.php" method="post">
  <table>
    <tr><th>Имя</th><th>Текст</th><th>Рейтинг</th><th>Действие</th></tr>
    <?php foreach($reviews as $i=>$r): ?>
    <tr>
      <td><input type="text" name="reviews[<?php echo $i?>][name]" value="<?php echo htmlspecialchars($r['name'])?>"></td>
      <td><textarea name="reviews[<?php echo $i?>][text]"><?php echo htmlspecialchars($r['text'])?></textarea></td>
      <td><select name="reviews[<?php echo $i?>][rating]">
        <option value="5" <?php if($r['rating']==5) echo 'selected';?>>5 ⭐</option>
        <option value="4" <?php if($r['rating']==4) echo 'selected';?>>4 ⭐</option>
        <option value="3" <?php if($r['rating']==3) echo 'selected';?>>3 ⭐</option>
      </select></td>
      <td><button class="btn" type="submit" name="action" value="save_reviews">Сохранить</button></td>
    </tr>
    <?php endforeach; ?>
  </table>
</form>
</body></html>