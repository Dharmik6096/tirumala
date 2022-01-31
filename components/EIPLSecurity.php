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
        $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
    }

    function Encrypt($plaintext, $dpu_key = NULL, $dpu_type = 8) {
        if ($dpu_type == 32) {
            $key = pack('H*', $dpu_key);
            $iv = pack('H*', "00000000000000000000000000000000");
            $plaintext = str_pad($plaintext, (floor(strlen($plaintext) / 16) + 1) * 16, "?");
            $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
            $ciphertext_base64 = base64_encode($ciphertext);
        } else {
            $this->key = pack('H*', $dpu_key);
            $this->setDpuKey();
            $plaintext = str_pad($plaintext, (floor(strlen($plaintext) / 16) + 1) * 16, "=");
            $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $plaintext, MCRYPT_MODE_ECB, $this->iv);
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
                $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv);
            } else {
                $plaintext_dec = FALSE;
            }
        } else {
            $this->key = pack('H*', $dpu_key);
            $this->setDpuKey();
            $ciphertext_dec = base64_decode($ciphertext_base64, TRUE);
            if ($ciphertext_dec) {
                $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $ciphertext_dec, MCRYPT_MODE_ECB, $this->iv);
            } else {
                $plaintext_dec = FALSE;
            }
        }
        if ($plaintext_dec && strlen($plaintext_dec) != strlen(utf8_decode($plaintext_dec))) {
            $plaintext_dec = FALSE;
        }
        return $plaintext_dec;
    }

}

?> 