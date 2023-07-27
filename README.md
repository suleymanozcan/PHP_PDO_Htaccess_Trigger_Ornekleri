Bazı fonksiyon ve kodların açıklaması için bir önceki repo'ya bakabilirsiniz.
# PHP_PDO_Ornekleri : https://github.com/suleymanozcan/PHP_PDO_Ornekleri


# PHP_PDO_Htaccess_Trigger_Ornekleri

Bu çalışma da bazı değişikler görebilirsiniz. 
bunlardan 1 tanesi 
```
PDO::FETCH_ASSOC 
```
kullanmak yerine 
```
PDO::FETCH_OBJ
``` 
kullandım.
çünkü içeriği 
```
$results['id']
```
yerine 
```
$results->id 
```
şeklinde kullanmayı daha çok seviyorum.

FETCH_ASSOC ve FETCH_OBJ arasında çok ufak bir bellek kullanım farkı bulunmaktadır ve yüksek miktarda veri çektiğinizde bu, az da olsa bir fark yaratır. Ancak eğer çok büyük miktarda bir veri çekmeyecekseniz, PDO::FETCH_OBJ kullanmanızın herhangi bir zararı olmaz. Ben genellikle bu şekilde kullanmayı daha çok tercih ederim.



# TRIGGER Oluşturma & Kullanımı ve Nedeni
Genel Görünüm : https://prnt.sc/CRyRZuNXKaAM

# INSERT Trigger :
Yeni ürün eklendiğinde ilgili kategorinin total sütununu +1 arttırma :

https://prnt.sc/CKx1C0Y1sH7X
```
BEGIN
UPDATE categories
SET total = total + 1
WHERE id = NEW.cat_id;
END
```

# UPDATE Trigger :
Ürün düzenlendiğinde ilgili kategorinin total sütununu +1 veya sabit tutma :

https://prnt.sc/6Cci5JGfQAjO
```
BEGIN
IF OLD.cat_id != NEW.cat_id THEN
UPDATE categories
SET total = total - 1
WHERE id = OLD.cat_id;

        UPDATE categories
        SET total = total + 1
        WHERE id = NEW.cat_id;
    END IF;
END
```

# DELETE Trigger :
Ürün silindiğinde ilgili kategorinin total sütununu -1 düşürme :

https://prnt.sc/dTsskfgk5jS-
```
BEGIN
    UPDATE categories
    SET total = total - 1
    WHERE id = OLD.cat_id;
END
```

demo3site_site@localhost kısmındaki demo3site_site benim database kullanıcı adım. Bu alana siz kendi database kullanıcı
adınızı yazacaksınız.
categories_total_insert, categories_total_update, categories_total_delete kısımları sadece name.
Şahsen ben bu tarz işlemlerde hep AFTER seçerim. Önce benim işlemimi yapsın sonra ne yapıyorsa yapsın diye :)


Normalde bu işlemi ürünü eklerken, silerken veya güncelleme yaparken kontrolünü sağlayıp yapabiliriz.
Lakin ne gerek var. PHPMYADMIN reis bizim için gül gibi yapıyor. Böylece bizde ekstra ekstra kontrol ve sorgudan kurtuluyoruz.
Burada asıl konu aslında +1, -1 den kurtulmak değil. Aslında bu işin tiyatro ve basic kısmı asıl konu şu
index.php içindeki son 5 kategori kısmından buna örnek vermek gerekirse. Şimdi biz bu alanda total sayıyı direk
kategoriye yazmamış olsaydık ne yapacaktık. Aşağıdaki foreach'de gidecektik. product tablosundaki cat_id'ler ile 
categories->id'yi eşleştirip count alacaktık. bu da her foreach döngüsünde tekrar tekrar sorgu demek.
İster yüksek kullanıcılı olsun ister düşük farketmez her türlü kaynak tüketimine neden olacaktı.
O yüzden trigger yazıp hem kontrolleri trigger'a yıktık hemde ekstra sorgudan kurtulduk.
Burada yaptığımız işlemin aynısını brands içinde yapacağız.

```
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
```


----

# Bilgi 1
fontawesome kullanmak yerine içeriği daha sade bir şekilde sunmak için
https://www.amp-what.com adresindeki karakterleri kullandım. O yüzden garibinize gitmesin.

↪   🔎   ✍   🗑

# Bilgi 2
Hazır bir tooltip kütüphanesi kullanıp yine kod karmaşasına girmek istemedim. O yüzden basic seviye de bir css yazdım.
```
.tooltip                    { position:relative; display:inline-block; font-size:12px;}
.tooltip .tooltiptext       { visibility:hidden; width:130px; background-color:#555; color:#fff; text-align:center; padding:5px 0; border-radius:6px; position:absolute; z-index:1; bottom:125%; left:50%; margin-left:-60px; opacity:0; transition:opacity .3s}
.tooltip:hover .tooltiptext { visibility:visible; opacity:1}
```

