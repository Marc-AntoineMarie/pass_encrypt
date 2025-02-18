<?php
/**
 * Classe pour gérer le chiffrement/déchiffrement des données sensibles
 */
class Encryption {
    private static $key;
    private static $cipher = "aes-256-cbc";
    
    /**
     * Initialise la clé de chiffrement depuis l'environnement
     * @throws Exception si la clé n'est pas configurée
     */
    private static function initKey() {
        if (!isset(self::$key)) {
            $envKey = getenv('ENCRYPTION_KEY');
            if (!$envKey) {
                throw new Exception("ENCRYPTION_KEY n'est pas définie dans l'environnement");
            }
            if (strlen($envKey) < 32) {
                throw new Exception("ENCRYPTION_KEY doit faire au moins 32 caractères");
            }
            self::$key = $envKey;
        }
    }
    
    /**
     * Chiffre une chaîne
     */
    public static function encrypt($data) {
        if (empty($data)) {
            throw new Exception("Les données à chiffrer ne peuvent pas être vides");
        }
        
        self::initKey();
        
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted = openssl_encrypt($data, self::$cipher, self::$key, 0, $iv);
        
        if ($encrypted === false) {
            throw new Exception("Échec du chiffrement : " . openssl_error_string());
        }
        
        return base64_encode($iv . $encrypted);
    }
    
    /**
     * Déchiffre une chaîne
     */
    public static function decrypt($data) {
        if (empty($data)) {
            throw new Exception("Les données à déchiffrer ne peuvent pas être vides");
        }
        
        self::initKey();
        
        try {
            $data = base64_decode($data);
            if ($data === false) {
                throw new Exception("Données base64 invalides");
            }
            
            $ivlen = openssl_cipher_iv_length(self::$cipher);
            if (strlen($data) <= $ivlen) {
                throw new Exception("Données chiffrées invalides");
            }
            
            $iv = substr($data, 0, $ivlen);
            $encrypted = substr($data, $ivlen);
            
            $decrypted = openssl_decrypt($encrypted, self::$cipher, self::$key, 0, $iv);
            if ($decrypted === false) {
                throw new Exception("Échec du déchiffrement : " . openssl_error_string());
            }
            
            return $decrypted;
            
        } catch (Exception $e) {
            throw new Exception("Erreur de déchiffrement : " . $e->getMessage());
        }
    }
}
