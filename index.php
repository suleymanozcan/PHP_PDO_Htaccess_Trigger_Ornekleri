<?php
define("ROOT",dirname(__FILE__));
require(ROOT.'/config.php');
$MaxLimit   = 5;
$currency   = "TL";
if($delete == 'delete'){
    $where  = ['id' => $id];
    $result = $db->delete('products', $where);
    $sonuc  = $result->message;
}
$product    = $db->query("SELECT products.id, products.name, products.url, products.price, products.vat, products.stock_code, products.stock_quantity, categories.name as category_name, brands.name as brand_name FROM products LEFT JOIN categories on products.cat_id=categories.id LEFT JOIN brands on products.bra_id=brands.id ORDER BY products.id DESC LIMIT $MaxLimit")->fetchAll(PDO::FETCH_OBJ);
$brand      = $db->query("SELECT * FROM brands ORDER BY id DESC LIMIT $MaxLimit")->fetchAll(PDO::FETCH_OBJ);
$category   = $db->query("SELECT * FROM categories ORDER BY id DESC LIMIT $MaxLimit")->fetchAll(PDO::FETCH_OBJ);
/*
 * categories ve brands tablosunda select * from kullanmamın sebebi gelecek sutunların zaten çoğunluğunu kullanacağım
 * o yüzden select * from yaptım. eğer product'daki gibi bir sorgunuz varsa tabi ki optimize için ihtiyacınız olanları
 * çekmeniz yararına olacaktır. bir de şu var zaten limit olduğu için öyle kullandım.
 * böylece her 2 kullanımı da görmüş olursunuz diye düşündüm.
*/

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
    <h1>Son Eklenen 5 Ürün</h1>
    <table>
        <thead>
            <tr>
                <th class="text-left">Stok Kodu</th>
                <th class="text-left">Ürün Adı</th>
                <th class="text-left">Kategori</th>
                <th class="text-left">Marka</th>
                <th>Miktarı</th>
                <th>Fiyatı</th>
                <th>KDV</th>
                <th>Bilgiler</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($product as $products):
            $category_name = $products->category_name ?? '-';
            $brand_name    = $products->brand_name ?? '-';
            echo "
                <tr>
                    <td class='text-left'>{$products->stock_code}</td>
                    <td>{$products->name}</td>
                    <td>{$category_name}</td>
                    <td>{$brand_name}</td>
                    <td class='center'>{$products->stock_quantity}</td>
                    <td class='center'>{$products->price} {$currency}</td>
                    <td class='center'>%{$products->vat}</td>
                    <td class='center'>
                        ".islemler('products', $products->url, $products->id)."
                    </td>
                </tr>
            ";
        endforeach;
        ?>
        </tbody>
    </table>
    <h1>Son Eklenen 5 Kategori</h1>
    <table>
        <thead>
        <tr>
            <th class="text-left">Kategori Adı</th>
            <th class="text-left">Özellik Tanımı</th>
            <th>Ürün Sayısı</th>
            <th>Bilgiler</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($category as $categories):
            echo "
                <tr>
                    <td>{$categories->name}</td>
                    <td>{$categories->total}</td>
                    <td class='center'>{$categories->total}</td>
                    <td class='center'>
                        ".islemler('categories', $categories->url, $categories->id)."
                    </td>
                </tr>
            ";
        endforeach;
        ?>
        </tbody>
    </table>
    <h1>Son Eklenen 5 Marka</h1>

</div>
<footer>
    Copyright Pure PHP. Designed by <a href="https://codermingle.dev/@suleymanozcan" target="_blank">Süleyman Zuckerberg</a>
</footer>
</body>
</html>
