<?php
$path = $_SERVER['DOCUMENT_ROOT']."/";
require($path.'config.php');
$MaxLimit   = 10;
$sonuc      = "";
if($delete == 'delete'){
    $where = ['id' => $id];
    $result = $db->delete('categories', $where);
    $sonuc = $result->message;
}

/* PAGE LİMİT AYARLARI BAŞLANGIÇ */
if($page<2){ $page_='0'; } else { $page_= $page-1;}
$page_limit      = $MaxLimit*$page_;
$categories_total    = $db->query("SELECT count(id) as toplam FROM categories")->fetch(PDO::FETCH_ASSOC);
$categories_total    = $categories_total['toplam'];
$category_pages     = ceil($categories_total/$MaxLimit);
/* PAGE LİMİT AYARLARI BİTİŞ */

$category      = $db->query("SELECT * FROM categories ORDER BY id DESC LIMIT $page_limit, $MaxLimit")->fetchAll(PDO::FETCH_OBJ);
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
    <h1>Kategori Listesi</h1>
    <a class="button" href="/categories-add">Yeni Kategori Ekle</a>
    <table>
        <thead>
        <tr>
            <th class="text-left">Kategori Adı</th>
            <th>Ürün Sayısı</th>
            <th>Bilgiler</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(empty($category)){
            echo "<tr><td colspan='3' class='center'>Kayıtlı bir kategori bulunamadı.</td></tr>";
        } else {
            foreach ($category as $categories):
                echo "
                    <tr>
                        <td>{$categories->name}</td>
                        <td class='center'><a href='categories-products/{$categories->url}'>{$categories->total}</a></td>
                        <td class='center'>
                            ".islemler('categories', $categories->url, $categories->id)."
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
        <a href='categories/pages/{$i}' class='pages {$class}'>{$i}</a>
        ";
    }
    ?>
</div>
<?php include(THEME.'footer.php'); ?>
</body>
</html>
