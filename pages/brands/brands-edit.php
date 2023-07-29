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
        $result         = $db->update('brands', $where, $data);
        $sonuc          = $result->message;
    } else {
        $sonuc          = '<center><b>Lütfen tekrar deneyiniz</b></center>';
    }
}
$brand        = $db->prepare("SELECT * FROM brands WHERE id = :id");
$brand->execute([':id' => $id]);
$brands       = $brand->fetch(PDO::FETCH_OBJ);
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
    if ($brands === false) {
        echo "<center><b>Marka Bulunamadı...</b></center>";
    } else { ?>
    <form action="brands-edit/<?php echo $id; ?>/editing" method="post">
        <h1>Markayı Düzenle</h1>
        <a class="button" href="/brands">Marka Listesine Dön</a>
        <input type="hidden" name="timeout" value="<?php echo $time; ?>">
        <label class="left">Marka adı</label>
        <input class="left" type="text" name="name" value="<?php echo $brands->name; ?>" minlength="3" required>
        <button>GÜNCELLE</button>
    </form>
    <?php } ?>

</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
