<?php
$path = $_SERVER['DOCUMENT_ROOT']."/";
require($path.'config.php');
$MaxLimit   = 10;
$sonuc      = "";
if($delete == 'delete'){
    $where = ['id' => $id];
    $result = $db->delete('brands', $where);
    $sonuc = $result->message;
}

/* PAGE LİMİT AYARLARI BAŞLANGIÇ */
if($page<2){ $page_='0'; } else { $page_= $page-1;}
$page_limit      = $MaxLimit*$page_;
$brands_total    = $db->query("SELECT count(id) as toplam FROM brands")->fetch(PDO::FETCH_ASSOC);
$brands_total    = $brands_total['toplam'];
$brand_pages     = ceil($brands_total/$MaxLimit);
/* PAGE LİMİT AYARLARI BİTİŞ */

$brand      = $db->query("SELECT * FROM brands ORDER BY id DESC LIMIT $page_limit, $MaxLimit")->fetchAll(PDO::FETCH_OBJ);
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
    <h1>Marka Listesi</h1>
    <a class="button" href="/brands-add">Yeni Marka Ekle</a>
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
    <?php
    for($i=1; $i<=$brand_pages;  $i++){
        $class = ($i == 1 && empty($page)) || $page == $i ? "active" : "";
        echo "
        <a href='brands/pages/{$i}' class='pages {$class}'>{$i}</a>
        ";
    }
    ?>
</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
