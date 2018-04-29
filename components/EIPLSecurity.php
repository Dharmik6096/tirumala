<?php

namespace app\components;

use yii\base\Component;

class EIPLSecurity extends Component {

    var $key;
    var $key_size;
    var $iv_size;
    var $iv;

    function __construct() {
        $this->key = pack('H*', "88155CAF698266445940AC42C7555212");
        $this->key_size = strlen($this->key);
        $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
    }

    function Encrypt($plaintext) {
        $plaintext = str_pad($plaintext, (floor(strlen($plaintext) / 16) + 1) * 16, "=");
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $plaintext, MCRYPT_MODE_ECB, $this->iv);
        $ciphertext = $ciphertext;
        $ciphertext_base64 = base64_encode($ciphertext);
        return $ciphertext_base64;
    }

    function Decrypt($ciphertext_base64) {
        $ciphertext_dec = base64_decode($ciphertext_base64);
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $ciphertext_dec, MCRYPT_MODE_ECB, $this->iv);
        return $plaintext_dec;
    }

}

?> 