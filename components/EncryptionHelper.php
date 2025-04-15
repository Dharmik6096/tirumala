<?php
namespace app\components;

class EncryptionHelper
{
    // Use a 32-byte key for AES-256 encryption
    // private static $key = 'your-secure-random-32-byte-key'; // Replace with a secure key
    private static $cipher = 'AES-256-CBC';

    /**
     * Retrieves the encryption key from the environment or config file.
     * 
     * @return string The encryption key.
     * @throws \Exception If the key is not set or invalid.
     */
    private static function getEncryptionKey()
    {
        $sbi_bank_integration_xml_config = \Yii::$app->params['sbi_bank_integration_xml_config'];
        $key = $sbi_bank_integration_xml_config['encryption_key'];

        if (!$key || strlen($key) < 16) {
            throw new \Exception('Encryption key is not set or invalid.');
        }

        return $key;
    }

    /**
     * Encrypt data using AES-256-CBC.
     *
     * @param string $data The data to encrypt.
     * @return string The encrypted data in base64 format.
     */
    public static function encrypt($data, $isFile = false)
    {
        $fileData = $data;
        if ($isFile) {
            if (!file_exists($data)) {
                throw new \InvalidArgumentException("File does not exist: {$data}");
            }
            $fileData = file_get_contents($data);
        }

        $key = self::getEncryptionKey();
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $iv = random_bytes($ivLength);
        $encrypted = openssl_encrypt($fileData, self::$cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new \RuntimeException('Encryption failed.');
        }

        $encryptedData = base64_encode($iv . $encrypted);

        if ($isFile) {
            $encryptedFilePath = $data;
            if (false === file_put_contents($encryptedFilePath, $encryptedData)) {
                throw new \RuntimeException("Failed to write encrypted file: {$encryptedFilePath}");
            }
            return $encryptedFilePath;
        }

        return $encryptedData;
    }

    /**
     * Decrypt data using AES-256-CBC.
     *
     * @param string $encryptedData The encrypted data in base64 format.
     * @return string The decrypted data.
     */
    public static function decrypt($encryptedData, $isFile = false)
    {
        $key = self::getEncryptionKey();
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $fileData = $encryptedData;
        if ($isFile) {
            $fileData = file_get_contents($fileData);    
            if ($fileData === false) {
                throw new \RuntimeException('Decryption failed: Unable to read the file.');
            }
        }
        $dencryptedData = base64_decode($fileData);

        if ($dencryptedData === false) {
            throw new \RuntimeException('Decryption failed: Invalid base64 format.');
        }

        $iv = substr($dencryptedData, 0, $ivLength);
        $encrypted = substr($dencryptedData, $ivLength);

        $decrypted = openssl_decrypt($encrypted, self::$cipher, $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new \RuntimeException('Decryption failed.');
        }

        if ($isFile) {
            $dencryptedFilePath = $encryptedData;
            file_put_contents($dencryptedFilePath, $decrypted);
            return $dencryptedFilePath;
        }

        return $decrypted;
    }
}

?>