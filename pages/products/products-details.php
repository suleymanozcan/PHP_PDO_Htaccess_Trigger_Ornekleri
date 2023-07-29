<?php
$path = $_SERVER['DOCUMENT_ROOT']."/";
require($path.'config.php');
$product        = $db->prepare("SELECT products.*, categories.name as category_name, categories.url as categories_url, brands.name as brand_name, brands.url as brand_url FROM products LEFT JOIN categories on products.cat_id=categories.id LEFT JOIN brands on products.bra_id=brands.id WHERE products.url = :url");
$product->execute([':url' => $url]);
$products       = $product->fetch(PDO::FETCH_OBJ);
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
        <h1>Ürün Detayı</h1>
        <a class="button" href="/products">Ürün Listesine Dön</a>
        <?php
        if ($products === false) {
            echo "<center><b>Ürün Bulunamadı...</b></center>";
        } else { ?>
        <label class="left">Kategori Seçiniz</label>
        <input class="left" type="text" value="<? echo $products->category_name; ?>" disabled>

        <label class="left">Marka Seçiniz</label>
        <input class="left" type="text" value="<? echo $products->brand_name; ?>" disabled>

        <label class="left">Ürün adı</label>
        <input class="left" type="text" name="name" value="<? echo $products->name; ?>" disabled>
        <label class="left">Stok Kodu</label>
        <input class="left" type="text" name="stock_code" value="<?=$products->stock_code; ?>" disabled>
        <label class="left">Stok Miktarı</label>
        <input class="left" type="text" name="stock_quantity" value="<? echo $products->stock_quantity; ?>" disabled>
        <label class="left">Ürün Fiyatı</label>
        <input class="left" type="text" name="price" value="<? echo $products->price; ?>" disabled>
        <label class="left">Ürün KDV Oranı</label>
        <input class="left" type="text" name="vat"  value="<? echo $products->vat; ?>" disabled>
        <label class="left">Ürün Açıklaması</label>

        <div style="clear: both;">
            <hr />
            <? echo nl2br($products->details); ?>
            <hr />
        </div>
        <img src="/upload/<? echo $products->image; ?>" width="400" height="400" />
        <? } ?>

</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
