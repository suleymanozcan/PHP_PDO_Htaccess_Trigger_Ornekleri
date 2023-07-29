<?php
$path = $_SERVER['DOCUMENT_ROOT']."/";
require($path.'config.php');
$sonuc              = "";
$time               = time();
if($add == 'add') {
    if ($_SESSION['time'] == $timeout) {
        unset($_SESSION['time']);
        $url            = createPermalink($name);
        $check_url      = URLchecked('categories',$url);
        $data  = array(
            'name'            => $name,
            'url'             => $check_url
        );
        $result         = $db->insert('categories', $data);
        $sonuc          = $result->message;
    } else {
        $sonuc          = '<center><b>Lütfen tekrar deneyiniz</b></center>';
    }
}
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
    <?php echo $sonuc; ?>
    <form action="categories-add/added" method="post">
        <h1>Yeni Kategori Ekle</h1>
        <a class="button" href="/categories">Kategori Listesine Dön</a>
        <input type="hidden" name="timeout" value="<? echo $time; ?>">
        <label class="left">Kategori adı</label>
        <input class="left" type="text" name="name" minlength="3" required>
        <button>KAYDET</button>
    </form>

</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
