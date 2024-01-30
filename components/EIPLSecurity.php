<?php

namespace app\components;

use yii\base\Component;

class EIPLSecurity extends Component {

    var $key;
    var $key_size;
    var $iv_size;
    var $iv;

    function setDpuKey() {
        $this->key_size = strlen($this->key);
        //$this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        //$this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    }

    function Encrypt($plaintext, $dpu_key = NULL, $dpu_type = 8, $add_char = '') {
        if ($dpu_type == 32) {
            $key = pack('H*', $dpu_key);
            $iv = pack('H*', "00000000000000000000000000000000");
            $plaintext = str_pad($plaintext, (floor(strlen($plaintext) / 16) + 1) * 16, "?");
            //  $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
            $ciphertext = openssl_encrypt($plaintext, 'aes-128-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
            $ciphertext_base64 = base64_encode($ciphertext);
        } else {
            $this->key = pack('H*', $dpu_key);
            $this->setDpuKey();
            $plaintext = str_pad($plaintext, (floor(strlen($plaintext) / 16) + 1) * 16, "=");
            //  $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $plaintext . $add_char, MCRYPT_MODE_ECB, $this->iv);
            $ciphertext = openssl_encrypt($plaintext . $add_char, 'aes-128-ecb', $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv);
            $ciphertext_base64 = base64_encode($ciphertext);
        }
        return $ciphertext_base64;
    }

    function Decrypt($ciphertext_base64, $dpu_key = NULL, $dpu_type = 8) {
        if ($dpu_type == 32) {
            $key = pack('H*', $dpu_key);
            $iv = pack('H*', "00000000000000000000000000000000");
            $ciphertext_dec = base64_decode($ciphertext_base64, TRUE);
            if ($ciphertext_dec) {
                //   $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv);
                $plaintext_dec = openssl_decrypt($ciphertext_dec, 'aes-128-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
            } else {
                $plaintext_dec = FALSE;
            }
        } else {
            $this->key = pack('H*', $dpu_key);
            $this->setDpuKey();
            $ciphertext_dec = base64_decode($ciphertext_base64, TRUE);
            if ($ciphertext_dec) {
                //$plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $ciphertext_dec, MCRYPT_MODE_ECB, $this->iv);
                $plaintext_dec = openssl_decrypt($ciphertext_dec, 'aes-128-ecb', $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
            } else {
                $plaintext_dec = FALSE;
            }
        }
        if ($plaintext_dec && strlen($plaintext_dec) != strlen(iconv('ISO-8859-1', 'UTF-8', $plaintext_dec))) {
            $plaintext_dec = FALSE;
        }
        return $plaintext_dec;
    }

}
