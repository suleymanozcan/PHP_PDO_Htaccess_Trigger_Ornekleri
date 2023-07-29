<?php
$path = $_SERVER['DOCUMENT_ROOT']."/";
require($path.'config.php');
$sonuc              = "";
$time               = time();
if($edit == 'edit') {
    if ($_SESSION['time'] == $timeout) {
        unset($_SESSION['time']);
        $data  = array(
            'name'            => $name,
        );
        $where = array(
            'id'              => $id
        );
        $result         = $db->update('categories', $where, $data);
        $sonuc          = $result->message;
    } else {
        $sonuc          = '<center><b>Lütfen tekrar deneyiniz</b></center>';
    }
}
$category        = $db->prepare("SELECT * FROM categories WHERE id = :id");
$category->execute([':id' => $id]);
$categories       = $category->fetch(PDO::FETCH_OBJ);
$_SESSION['time']   = $time;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Pure PHP</title>
    <meta name="description"        content="Bla bla bla." />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="/">
    <link href="minata/css.css?time=<?php echo time(); ?>" rel="stylesheet">
</head>
<body>
<?php include(THEME.'header.php'); ?>
<div class="container">
    <?php
    echo $sonuc;
    if ($categories === false) {
        echo "<center><b>Kategori Bulunamadı...</b></center>";
    } else { ?>
    <form action="categories-edit/<?php echo $id; ?>/editing" method="post">
        <h1>Kategoriyi Düzenle</h1>
        <a class="button" href="/categories">Kategori Listesine Dön</a>
        <input type="hidden" name="timeout" value="<?php echo $time; ?>">
        <label class="left">Marka adı</label>
        <input class="left" type="text" name="name" value="<?php echo $categories->name; ?>" minlength="3" required>
        <button>GÜNCELLE</button>
    </form>
    <?php } ?>

</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
