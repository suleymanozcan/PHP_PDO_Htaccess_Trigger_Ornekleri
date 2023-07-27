<?php
function createPermalink($str) {
    $str = mb_strtolower($str, 'UTF-8');
    $search = array('ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç');
    $replace = array('i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c');
    $str = str_replace($search, $replace, $str);
    $str = preg_replace('/[^a-zA-Z0-9\s]/', '', $str);
    $str = str_replace(' ', '-', $str);
    return $str;
}

function image_upload() {
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


function islemler($location, $url, $id)
{
    if ($location == 'products') {
        $islemler = "
            <div class='tooltip'>
                <a class='icon' href='product-details/{$url}'>🔎</a>
                <span class='tooltiptext'>Ürün Detayı</span>
            </div>
            <div class='tooltip'>
                <a class='icon' href='product-edit/{$id}'>✍</a>
                <span class='tooltiptext'>Ürün Düzenle</span>
            </div>
            <div class='tooltip'>
                <a class='icon' href='product-image/{$id}'>📁</a>
                <span class='tooltiptext'>Ürün Resim Güncelle</span>
            </div>
            <div class='tooltip'>
                <a class='icon' href='products/{$id}/delete'>🗑</a>
                <span class='tooltiptext'>Ürünü Sil</span>
            </div>
        ";
    }
    return $islemler;
}

function emptyValue($value){

}
?>