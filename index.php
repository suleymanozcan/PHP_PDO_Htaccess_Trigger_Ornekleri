<?php
$path = $_SERVER['DOCUMENT_ROOT']."/";
require($path.'config.php');
$MaxLimit   = 5;
$currency   = "TL";

$product    = $db->query("SELECT * FROM Product_Views ORDER BY id DESC LIMIT $MaxLimit")->fetchAll(PDO::FETCH_OBJ);
// $product için readme de yer alan VIEW kısmına bakınız.
$brand      = $db->query("SELECT * FROM brands ORDER BY id DESC LIMIT $MaxLimit")->fetchAll(PDO::FETCH_OBJ);
$category   = $db->query("SELECT * FROM categories ORDER BY id DESC LIMIT $MaxLimit")->fetchAll(PDO::FETCH_OBJ);
/*
 * categories ve brands tablosunda select * from kullanmamın sebebi gelecek sutunların zaten çoğunluğunu kullanacağım
 * o yüzden select * from yaptım. $product değerindeki SQL sorgusu için Readme.md de yer alan VIEW oluşturma kısmına
 * bakmanız yararınıza olacaktır. böylece her 2 kullanımı da görmüş olursunuz diye düşündüm.
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
    <h1>Son Eklenen <?php echo $MaxLimit; ?> Ürün</h1>
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
    <h1>Son Eklenen <?php echo $MaxLimit; ?> Kategori</h1>
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
                    <td class='center'><a href='categories-products/{$categories->url}'>{$categories->total}</a></td>
                    <td class='center'>
                        ".islemler('categories', $categories->url, $categories->id)."
                    </td>
                </tr>
            ";
        endforeach;
        ?>
        </tbody>
    </table>
    <h1>Son Eklenen <?php echo $MaxLimit; ?> Marka</h1>
    <table>
        <thead>
        <tr>
            <th class="text-left">Marka Adı</th>
            <th>Ürün Sayısı</th>
            <th>Bilgiler</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(empty($brand)){
            /*
             * Yukarıdaki kategori ve ürünlerde bilerek yapmadım. Farkı görün diye.
             * Bu arada if'i önceye almamın nedeni eğer boş ise foreach'e girmesine gerek yok diye yaptım.
             * Eğer isterseniz if else yerine direk if kullanabilirsiniz. Ama tavsiyem bu şekilde kullanmanız olur.
            */
            echo "<tr><td colspan='3' class='center'>Kayıtlı bir marka bulunamadı.</td></tr>";
        } else {
            foreach ($brand as $brands):
                echo "
                    <tr>
                        <td>{$brands->name}</td>
                        <td class='center'><a href='brands-products/{$brands->url}'>{$brands->total}</a></td>
                        <td class='center'>
                            ".islemler('brands', $brands->url, $brands->id)."
                        </td>
                    </tr>
                ";
            endforeach;
        }
        ?>
        </tbody>
    </table>
</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
