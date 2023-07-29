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
            'price'           => $price,
            'vat'             => $vat,
            'stock_code'      => $stock_code,
            'stock_quantity'  => $stock_quantity,
            'details'         => $details,
            'cat_id'          => $cat_id,
            'bra_id'          => $bra_id
        );
        $where = array(
            'id'              => $id
        );
        $result         = $db->update('products', $where, $data);
        $sonuc          = $result->message;
    } else {
        $sonuc          = '<center><b>Lütfen tekrar deneyiniz</b></center>';
    }
}
$brand          = $db->query("SELECT id,name FROM brands ORDER BY name ASC")->fetchAll(PDO::FETCH_OBJ);
$category       = $db->query("SELECT id,name FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_OBJ);

$product        = $db->prepare("SELECT * FROM products WHERE id = :id");
$product->execute([':id' => $id]);
$products       = $product->fetch(PDO::FETCH_OBJ);
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
    if ($products === false) {
        echo "<center><b>Ürün Bulunamadı...</b></center>";
    } else { ?>
    <form action="products-edit/<?php echo $id; ?>/editing" method="post">
        <h1>Ürün Düzenle</h1>
        <a class="button" href="/products">Ürün Listesine Dön</a>
        <input type="hidden" name="timeout" value="<?php echo $time; ?>">
        <label class="left">Kategori Seçiniz</label>
        <select name="cat_id" class="left">
            <?php
            foreach ($category as $categories):
                $selected = '';
                if($categories->id == $products->cat_id){
                    $selected = 'selected';
                }
                echo "<option value='{$categories->id}' {$selected}>{$categories->name}</option>";
            endforeach;
            ?>
        </select>
        <label class="left">Marka Seçiniz</label>
        <select name="bra_id" class="left">
            <?php
            foreach ($brand as $brands):
                $selected = '';
                if($brands->id == $products->bra_id){
                    $selected = 'selected';
                }
                echo "<option value='{$brands->id}' {$selected}>{$brands->name}</option>";
            endforeach;
            ?>
        </select>
        <label class="left">Ürün adı</label>
        <input class="left" type="text" name="name" value="<? echo $products->name; ?>" required>
        <label class="left">Stok Kodu</label>
        <input class="left" type="text" name="stock_code" value="<?=$products->stock_code; ?>" required>
        <label class="left">Stok Miktarı</label>
        <input class="left" type="text" name="stock_quantity" value="<? echo $products->stock_quantity; ?>">
        <label class="left">Ürün Fiyatı</label>
        <input class="left" type="text" name="price" value="<? echo $products->price; ?>" required>
        <label class="left">Ürün KDV Oranı</label>
        <input class="left" type="text" name="vat"  value="<? echo $products->vat; ?>">
        <label class="left">Ürün Açıklaması</label>
        <textarea class="left" name="details" required><? echo $products->details; ?></textarea>
        <button>GÜNCELLE</button>
    </form>
    <? } ?>

</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
