<?php
$path = $_SERVER['DOCUMENT_ROOT']."/";
require($path.'config.php');
$sonuc              = "";
$time               = time();
if($add == 'add') {
    if ($_SESSION['time'] == $timeout) {
        unset($_SESSION['time']);
        $url            = createPermalink($name).".html";
        $check_url      = URLchecked('products',$url);
        $images         = image_upload();

        $data  = array(
            'name'            => $name,
            'url'             => $check_url,
            'price'           => $price,
            'vat'             => $vat,
            'stock_code'      => $stock_code,
            'stock_quantity'  => $stock_quantity,
            'image'           => $images,
            'details'         => $details,
            'cat_id'          => $cat_id,
            'bra_id'          => $bra_id
        );
        $result         = $db->insert('products', $data);
        $sonuc          = $result->message;
    } else {
        $sonuc          = '<center><b>Lütfen tekrar deneyiniz</b></center>';
    }
}
$_SESSION['time']   = $time;
$brand      = $db->query("SELECT id,name FROM brands ORDER BY name ASC")->fetchAll(PDO::FETCH_OBJ);
$category   = $db->query("SELECT id,name FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_OBJ);

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
    <form action="products-add/added" method="post" enctype="multipart/form-data">
        <h1>Yeni Ürün Ekle</h1>
        <a class="button" href="/products">Ürün Listesine Dön</a>
        <input type="hidden" name="timeout" value="<? echo $time; ?>">
        <label class="left">Kategori Seçiniz</label>
        <select name="cat_id" class="left">
            <?php
            foreach ($category as $categories):
                echo "<option value='{$categories->id}'>{$categories->name}</option>";
            endforeach;
            ?>
        </select>
        <label class="left">Marka Seçiniz</label>
        <select name="bra_id" class="left">
            <?php
            foreach ($brand as $brands):
                echo "<option value='{$brands->id}'>{$brands->name}</option>";
            endforeach;
            ?>
        </select>

        <label class="left">Ürün adı</label>
        <input class="left" type="text" name="name" minlength="3" required>
        <label class="left">Stok Kodu</label>
        <input class="left" type="text" name="stock_code" required>
        <label class="left">Stok Miktarı</label>
        <input class="left" type="number" name="stock_quantity">
        <label class="left">Ürün Fiyatı</label>
        <input class="left" type="number" name="price" step="0.01" min="0" required>
        <label class="left">Ürün KDV Oranı</label>
        <input class="left" type="number" name="vat" value="20">
        <label class="left">Ürün Resmi</label>
        <input class="left" type="file" name="image" accept="image/*">
        <label class="left">Ürün Açıklaması</label>
        <textarea class="left" name="details" required></textarea>
        <button>KAYDET</button>
    </form>

</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
