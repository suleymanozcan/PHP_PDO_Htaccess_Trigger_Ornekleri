<?
if (!defined('ROOT')) { header("Location: /"); exit(); }
// JSON TR KARAKTER SORUNU
const Sorunlu           = array("\u00fc","\u011f","\u0131","\u015f","\u00e7","\u00f6","\u00dc","\u011e","\u0130","\u015e","\u00c7","\u00d6");
const Duzeltilecek      = array("ü","ğ","ı","ş","ç","ö","Ü","Ğ","İ","Ş","Ç","Ö");
// JSON TR KARAKTER SORUNU BİTİŞ

Class Database extends PDO {
    protected $dbConfig = array();
    protected function createConfig() {
        $this->dbConfig['host']     = 'localhost';
        $this->dbConfig['username'] = 'demo3site_site';
        $this->dbConfig['password'] = 'wUD}#RLy$LNg';
        $this->dbConfig['dbname']   = 'demo3site_site';
    }
    function __construct() {
        $this->createConfig();
        $dsn = 'mysql:host=' . $this->dbConfig['host'] . ';dbname=' . $this->dbConfig['dbname'];
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            parent::__construct($dsn, $this->dbConfig['username'], $this->dbConfig['password'], $opt);
            $this->query("SET NAMES utf8");
        } catch (PDOException $e) {
            throw $e;
        }
    }


    public function insert($table, $array) {
        $columns        = implode(", ", array_keys($array));
        $placeholders   = ":" . implode(", :", array_keys($array));
        $sql            = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt           = $this->prepare($sql);
        foreach ($array as $key => $value) {
            $value = $this->JsonValue($value);
            $stmt->bindValue(":{$key}", $value);
        }
        return $this->executeStatement($stmt);
    }

    public function update($table, $conditions, $array) {
        $placeholders = array_map(function($key) {
            return "{$key} = :{$key}";
        }, array_keys($array));

        $placeholders       = implode(", ", $placeholders);
        $whereConditions    = implode(' AND ', array_map(function($k) { return "$k = :w_$k"; }, array_keys($conditions)));
        $sql                = "UPDATE {$table} SET {$placeholders} WHERE {$whereConditions}";
        $stmt               = $this->prepare($sql);

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":w_{$key}", $value);
        }
        foreach ($array as $key => $value) {
            $value = $this->sanitizeValue($value);
            $stmt->bindValue(":{$key}", $value);
        }

        return $this->executeStatementUpdate($stmt);
    }

    public function delete($table, $array) {
        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', array_map(function($k) { return "$k = :$k"; }, array_keys($array)));
        $stmt = $this->prepare($sql);
        foreach ($array as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        return $this->executeStatementDelete($stmt);
    }


    private function JsonValue($value) {
        if (is_string($value)) {
            json_decode($value);
            if (json_last_error() == JSON_ERROR_NONE) {
                $value = json_decode($value, true);
            }
        }
        if (is_string($value)) {
            $value = str_replace(Sorunlu, Duzeltilecek, $value);
        }
        return $value;
    }

    private function executeStatement($stmt) {
        $response = new stdClass();
        try {
            $stmt->execute();
            $response->status       = 1;
            $response->message      = "<center>Ekleme işlemi başarılı.</center>";
            $response->id           = $this->lastInsertId();
            return $response;
        } catch (PDOException $e) {
            $response->status       = 0;
            $response->message      = "Ekleme işlemi başarısız: " . $e->getMessage();
            $response->id           = null;
            return $response;
        }
    }

    private function executeStatementUpdate($stmt) {
        $response = new stdClass();
        try {
            $stmt->execute();
            $affectedRows = $stmt->rowCount();

            if ($affectedRows === 0) {
                $response->status   = 0;
                $response->message  = "<center><b>Güncelleme işlemi başarısız, hiçbir satır etkilenmedi.</b></center>";
                return $response;
            }

            $response->status   = 1;
            $response->message  = "<center>Güncelleme işlemi başarılı.</center>";
            return $response;
        } catch (PDOException $e) {
            $response->status   = 0;
            $response->message  = "Güncelleme işlemi başarısız: " . $e->getMessage();
            return $response;
        }
    }

    private function executeStatementDelete($stmt) {
        $response = new stdClass();
        try {
            $stmt->execute();
            $affected_rows = $stmt->rowCount();
            if ($affected_rows === 0) {
                $response->status = 0;
                $response->message = "<center><b>Bu ID ile silinecek bir kayıt bulunamadı.</b></center>";
            } else {
                $response->status = 1;
                $response->message = "<center>Kayıtlar başarıyla silindi.</center>";
            }
            return $response;
        } catch (PDOException $e) {
            $response->status = 0;
            $response->message = "Veritabanında belirtilen tablo bulunamadı: {$table}";
            return $response;
        }
    }
}
?>