<?php
$path = $_SERVER['DOCUMENT_ROOT']."/";
require($path.'config.php');
$MaxLimit   = 10;
$currency   = "TL";

$sonuc      = "";
if($delete == 'delete'){
    $where = ['id' => $id];
    $result = $db->delete('products', $where);
    $sonuc = $result->message;
}

/* PAGE LİMİT AYARLARI BAŞLANGIÇ */
if($page<2){ $page_='0'; } else { $page_= $page-1;}
$page_limit      = $MaxLimit*$page_;
$products_total    = $db->query("SELECT count(id) as toplam FROM products")->fetch(PDO::FETCH_ASSOC);
$products_total    = $products_total['toplam'];
$product_pages     = ceil($products_total/$MaxLimit);
/* PAGE LİMİT AYARLARI BİTİŞ */

$product      = $db->query("SELECT * FROM Product_Views ORDER BY id DESC LIMIT $page_limit, $MaxLimit")->fetchAll(PDO::FETCH_OBJ);
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
    <h1>Ürün Listesi</h1>
    <a class="button" href="/products-add">Yeni Ürün Ekle</a>
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
        if(empty($product)){
            echo "<tr><td colspan='8' class='center'>Kayıtlı bir ürün bulunamadı.</td></tr>";
        } else {
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
        }
        ?>
        </tbody>
    </table>
    <?php
    for($i=1; $i<=$product_pages;  $i++){
        $class = ($i == 1 && empty($page)) || $page == $i ? "active" : "";
        echo "
        <a href='products/pages/{$i}' class='pages {$class}'>{$i}</a>
        ";
    }
    ?>
</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
