<?php
function createPermalink($str, $locale = 'en') {
    $transliterator = Transliterator::create('Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove', Transliterator::FORWARD);
    $str = $transliterator->transliterate($str);
    $str = mb_strtolower($str, 'UTF-8');
    $str = preg_replace('/\s+/', '-', $str);
    $str = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $str);
    $str = trim($str, '-');
    return $str;
}

function URLchecked($table,$url) {
    global $db;
    $original_url   = $url;
    $count = 0;
    $suffix = 0;
    while (true) {
        $url_checked = $db->prepare("SELECT COUNT(*) as count FROM $table WHERE url = :url");
        $url_checked->execute([':url' => $url]);
        $result = $url_checked->fetch(PDO::FETCH_ASSOC);
        $count = $result['count'];
        if ($count === 0) {
            break;
        }
        $suffix += 1; // Sonek sayısını 1 artır
        $pos = strrpos($original_url, '.html');
        if ($pos !== false) {
            $url = substr($original_url, 0, $pos) . '-' . $suffix . substr($original_url, $pos);
        } else {
            $url = $original_url . '-' . $suffix;
        }
    }
    return $url;
}


function image_upload() {
    if(empty($_FILES['image']) || $_FILES['image']['size'] == 0){
        // Dosya seçilmediyse "none.png" dön ve işlemi sonlandır.
        return "none.png";
    }

    $izin_verilen_turler    = array('image/jpeg', 'image/pjpeg', 'image/png', 'image/webp');
    $izin_verilen_uzantilar = array('jpeg', 'jpg', 'png', 'webp');

    $dosya_adi 		        = $_FILES['image']['name'];
    $dosya_boyutu 	        = $_FILES['image']['size'];
    $dosya_turu 	        = $_FILES['image']['type'];
    $tmp_dosya_adi 	        = $_FILES['image']['tmp_name'];

    $uzanti                 = strtolower(pathinfo($dosya_adi, PATHINFO_EXTENSION));

    $finfo                  = finfo_open(FILEINFO_MIME_TYPE);
    $gercek_tur             = finfo_file($finfo, $tmp_dosya_adi);
    finfo_close($finfo);

    $hatali_dosya = !in_array($uzanti, $izin_verilen_uzantilar)
        || !in_array($gercek_tur, $izin_verilen_turler)
        || !in_array($dosya_turu, $izin_verilen_turler)
        || $dosya_boyutu == 0;

    if ($hatali_dosya) {
        return "none.png";
    } else {
        $yeni_dosya_adi = md5($dosya_adi . time()) . '.' . $uzanti;
        $yeni_dosya_yolu = UPLOAD . '/' . $yeni_dosya_adi;

        if (move_uploaded_file($tmp_dosya_adi, $yeni_dosya_yolu)) {
            if (filesize($yeni_dosya_yolu) < 2) {
                unlink($yeni_dosya_yolu);
                return "none.png";
            } else {
                return $yeni_dosya_adi;
            }
        } else {
            return "none.png";
        }
    }
}


function islemler($location, $url, $id){
    $location_array = [
        'products' => [
            ['products-details/'.$url, '🔎', 'Ürün Detayı', ''],
            ['products-edit/'.$id, '✍', 'Ürün Düzenle', ''],
            ['products/'.$id.'/delete', '🗑', 'Ürünü Sil', 'delete'],
        ],
        'categories' => [
            ['categories-products/'.$url, '🔎', 'Ürünleri Gör', ''],
            ['categories-edit/'.$id, '✍', 'Kategori Düzenle', ''],
            ['categories/'.$id.'/delete', '🗑', 'Kategoriyi Sil', 'delete'],
        ],
        'brands' => [
            ['brands-products/'.$url, '🔎', 'Ürünleri Gör', ''],
            ['brands-edit/'.$id, '✍', 'Markayı Düzenle', ''],
            ['brands/'.$id.'/delete', '🗑', 'Markayı Sil', 'delete'],
        ],
    ];

    $islemler = '';
    foreach ($location_array[$location] as $action) {
        $islemler .= "
            <div class='tooltip'>
                <a class='icon {$action[3]}' href='{$action[0]}'>{$action[1]}</a>
                <span class='tooltiptext'>{$action[2]}</span>
            </div>
        ";
    }
    return $islemler;
}

?>