<?php

namespace PWBox\Model;
//https://gist.github.com/joashp/a1ae9cb30fa533f4ad94

class Encryption
{
    protected $encrypt_method = "AES-256-CBC";
    protected $secret_key = 'This is my secret key';
    protected $secret_iv = 'This is my secret iv';
    protected $key;
    protected $iv;


    /*
     * $option: Encriptar(1) o desencriptar(2) string
     */
    public function __invoke(string $option, string $data)
    {
        $result = false;

        $this->key = hash('sha256', $this->secret_key);// hash
        $this->iv = substr(hash('sha256', $this->secret_iv), 0, 16);// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning

        if ($option == "encrypt") {
            $result = $this->encrypt($data);
        }
        if ($option == "decrypt") {
            $result = $this->decrypt($data);
        }

        return $result;
    }

    private function encrypt(String $data) {
        $output = openssl_encrypt($data, $this->encrypt_method, $this->key, 0, $this->iv);
        $output = base64_encode($output);//Per a la bbdd
        return $output;
    }

    private function decrypt($data) {
        $output = openssl_decrypt(base64_decode($data), $this->encrypt_method, $this->key, 0, $this->iv);
        return $output;
    }
}