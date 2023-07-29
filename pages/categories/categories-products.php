<?php
$path = $_SERVER['DOCUMENT_ROOT']."/";
require($path.'config.php');
$MaxLimit   = 10;
$currency   = "TL";

/* PAGE LİMİT AYARLARI BAŞLANGIÇ */
if($page<2){ $page_='0'; } else { $page_= $page-1;}
$page_limit      = $MaxLimit*$page_;
$category_total     = $db->prepare("SELECT count(id) as toplam FROM Product_Views WHERE categories_url = :url");
$category_total->execute([':url' => $url]);
$categories_total    = $category_total->fetch(PDO::FETCH_ASSOC);
$categories_total    = $categories_total['toplam'];
$category_pages     = ceil($categories_total/$MaxLimit);
/* PAGE LİMİT AYARLARI BİTİŞ */

$product         = $db->prepare("SELECT * FROM Product_Views WHERE categories_url = :url ORDER BY id DESC LIMIT $page_limit, $MaxLimit");
$product->execute([':url' => $url]);
$products        = $product->fetchAll(PDO::FETCH_OBJ);

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
    <h1>Kategori Ürün Listesi</h1>
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
        if(empty($products)){
            echo "<tr><td colspan='8' class='center'>Bu kategoriye kayıtlı ürün bulunamamıştır.</td></tr>";
        } else {
            foreach ($products as $product):
                $category_name = $product->category_name ?? '-';
                $category_name = $product->category_name ?? '-';
                echo "
                <tr>
                    <td class='text-left'>{$product->stock_code}</td>
                    <td>{$product->name}</td>
                    <td>{$category_name}</td>
                    <td>{$category_name}</td>
                    <td class='center'>{$product->stock_quantity}</td>
                    <td class='center'>{$product->price} {$currency}</td>
                    <td class='center'>%{$product->vat}</td>
                    <td class='center'>
                        " . islemler('products', $product->url, $product->id) . "
                    </td>
                </tr>
            ";
            endforeach;
        }
        ?>
        </tbody>
    </table>
    <?php
    for($i=1; $i<=$category_pages;  $i++){
        $class = ($i == 1 && empty($page)) || $page == $i ? "active" : "";
        echo "
        <a href='categories-products/{$url}/{$i}' class='pages {$class}'>{$i}</a>
        ";
    }
    ?>
</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
