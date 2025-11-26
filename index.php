<?php
    
    $tested = true;
?>
<html>
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Speed Test Encryption</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;700&display=swap" rel="stylesheet">

        <!-- CSSs -->
        <link rel="stylesheet" href="assets/css/normalize-reset.css" type="text/css"/>
        <link rel="stylesheet" href="assets/css/style.css" type="text/css"/>

        <!-- Icon -->
        <link rel="icon" href="icon.png" type="image/x-icon"/>
    </head>
    <body class="page">
        <aside class="info-area">
            <h1>Speed Test Encryption</h1>
            <p class="sub-title-desc">encryption list</p>
            <ul class="types-list">
                <li>
                    <strong>Guest (2x encryption)</strong>
                    <div class="max-size"><small>Max. size: 60 MB</small></div>                    
                    <ul class="algorithms">
                        <li class="info" title="The base64 encoding is a method of converting binary data into ASCII text format, which can be easily transmitted over networks that only support ASCII characters. ">
                            base64
                        </li>
                        <li class="info" title="AES-128-GCM, AES-192-GCM, and AES-256-GCM are symmetric encryption algorithms that use the Advanced Encryption Standard (AES) block cipher in Galois/Counter Mode (GCM) mode of operation. GCM is a mode of operation that provides both confidentiality and data integrity protection. The AES algorithm is a widely used symmetric encryption algorithm that is known for its speed, efficiency, and security.">
                            AES-128-GCM
                        </li>
                    </ul>
                </li>
                <li>
                    <strong>Basic (3x encryption)</strong>
                    <div class="max-size"><small>Max. size: 40 MB</small></div>                                        
                    <ul class="algorithms">
                        <li>base64</li>
                        <li>AES-128-GCM</li>
                        <li>AES-192-GCM</li>
                    </ul>
                </li>
                <li>
                    <strong>Advanced (4x encryption)</strong>
                    <div class="max-size"><small>Max. size: 20 MB</small></div>                                       
                    <ul class="algorithms">
                        <li>base64</li>
                        <li>AES-128-GCM</li>
                        <li>AES-192-GCM</li>
                        <li>AES-256-GCM</li>
                    </ul>
                </li>
                <li>
                    <strong>Admin (5x encryption)</strong>
                    <div class="max-size"><small>Max. size: 10 MB</small></div>                                        
                    <ul class="algorithms">
                        <li>base64</li>
                        <li>AES-128-GCM</li>                        
                        <li>AES-192-GCM</li>
                        <li>AES-256-GCM</li>
                        <li class="important info" title="ARIA-256-CBC is another symmetric encryption algorithm that uses the ARIA block cipher in Cipher Block Chaining (CBC) mode of operation. ARIA is a symmetric encryption algorithm that was developed by the Korean Agency for Technology and Standards. CBC is a mode of operation that provides confidentiality but does not provide data integrity protection.">
                            ARIA-256-CBC
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="php-v">
                <p><small>PHP VERSION: 7.1</small></p>                
                <!-- OpenSSL List -->
                <a class="open-list" href="list.php">Open-SSL available ciphers list</a>
             </div>              
        </aside>
        <main class="form-area">
            <!-- Form -->
            <form class="form" action="" method="get">
                <h2>New Speed Test</h2>
                <label>
                    Select a method:
                    <select name="method" id="method">
                        <option value=1>Guest</option>
                        <option value=2>Basic</option>
                        <option value=3>Advanced</option>
                        <option value=4>Admin</option>
                    </select>
                </label>
                <label>
                    Packet size (MB):
                    <input id="size" type="number" name="size" min="1" max="60" value="1"/>
                </label>
                <input type="submit" value="encrypt" name="submit"/>
            </form>
        </main>
    </body>
</html>


<?php

    // Start of the application
    if (isset($_GET['method']) and $_GET['size'] > 0) {
            
        // Gets the user data
        $method = $_GET['method'];
        $size = $_GET['size'];

        // Checks if the data matchs the allowed sizes
        if (checkValues($method, $size)) {
            echo '<div class="warning">Method maximum size exceed</div>';
            die();
        }                

        $data = random_bytes(1024 * 1024 * $size);  // Creates a rondom packet with the especified size (1024 * 1024 = 1 MB)

        $timers = encrypt($method, $data);          // Inits the encryption

        buildResults($method, $size, $timers[0], $timers[1]);

    } else { die(); }                               // The applications stops is no data was not sent

    
    // Checks the size os the data for each method
	function checkValues($method, $size) {
		
		if ($method == 1 and $size > 60) {
			return 1;
		} 
        
        elseif ($method == 2 and $size > 40) {
			return 1;
		} 

        elseif ($method == 3 and $size > 20) {
			return 1;
		} 
        
        elseif ($method == 4 and $size > 10) {
			return 1;
		}

        return 0;
	}

    // Writes on the screen the results
    function buildResults($method, $size, $encryptTime, $decryptTime) {

        $methodName = "";

        switch($method) {
            case 1: $methodName = "Guest"; break;
            case 2: $methodName = "Basic"; break;
            case 3: $methodName = "Advanced"; break;
            case 4: $methodName = "Admin"; break;
        }

        echo   '<section class="result">
                    <h3>Results</h3>
                    <div class="result-info">
                        <!-- Method -->
                        <div class="result-item">
                            <div class="result-value">'.$methodName.'</div>
                            <div class="result-title">Method</div>
                        </div>
                        <!-- Method -->
                        <div class="result-item">
                            <div class="result-value">'.$size.' MB</div>
                            <div class="result-title">Packet size</div>
                        </div>
                        <!-- Encrypt Time -->
                        <div class="result-item">
                            <div class="result-value timer">'.$encryptTime.'</div>
                            <div class="result-title">Encrypt Time (μs)</div>
                        </div>
                        <!-- Dencrypt Time -->
                        <div class="result-item">
                            <div class="result-value timer">'.$decryptTime.'</div>
                            <div class="result-title">Decrypt Time (μs)</div>
                        </div>
                    </div>
                </section>';
    }
    
    // Dencrypts the data
    function decrypt($method, $encryptedMsg, $key = "", $IV = "", $tag = ""){
        
        $timeToDecrypt = 0.0;

        switch ($method) {

            case 1:

                $cifra = "aes-128-gcm";

                $inicio = microtime(true);
                $mensagemCriptografada  = openssl_decrypt($encryptedMsg, $cifra, $key, 0, $IV, $tag);
                $mensagemCriptografada2 = base64_decode($mensagemCriptografada);
                $timeToDecrypt = microtime(true) - $inicio;

                break;

            case 2:

                $cifra  = "aes-128-gcm";
                $cifra2 = "aes-192-gcm";
                
                $inicio = microtime(true);
                $mensagemCriptografada3 = openssl_decrypt($encryptedMsg, $cifra2, $key[1], 0, $IV[1], $tag[1]);
                $mensagemCriptografada2 = openssl_decrypt($mensagemCriptografada3, $cifra, $key[0], 0, $IV[0], $tag[0]);
                $mensagemCriptografada  = base64_decode($mensagemCriptografada2);
                $timeToDecrypt = microtime(true) - $inicio;

                break;

            case 3:

                $cifra = "aes-128-gcm";
                $cifra2 = "aes-192-gcm";
                $cifra3 = "aes-256-gcm";
                
                $inicio = microtime(true);
                $mensagemCriptografada4 = openssl_decrypt($encryptedMsg, $cifra3, $key[2], 0, $IV[2], $tag[2]);
                $mensagemCriptografada3 = openssl_decrypt($mensagemCriptografada4, $cifra2, $key[1], 0, $IV[1], $tag[1]);
                $mensagemCriptografada2 = openssl_decrypt($mensagemCriptografada3, $cifra, $key[0], 0, $IV[0], $tag[0]);
                $mensagemCriptografada  = base64_decode($mensagemCriptografada2);
                $timeToDecrypt = microtime(true) - $inicio;
                
                break;

            case 4:

                $cifra = "aes-128-gcm";
                $cifra2 = "aes-192-gcm";
                $cifra3 = "aes-256-gcm";
                $cifra4 = "aria-256-cbc";
                
                $inicio = microtime(true);
                $mensagemCriptografada5 = openssl_decrypt($encryptedMsg, $cifra4, $key[3], 0, $IV[3], $tag[3]);
                $mensagemCriptografada4 = openssl_decrypt($mensagemCriptografada5, $cifra3, $key[2], 0, $IV[2], $tag[2]);
                $mensagemCriptografada3 = openssl_decrypt($mensagemCriptografada4, $cifra2, $key[1], 0, $IV[1], $tag[1]);
                $mensagemCriptografada2 = openssl_decrypt($mensagemCriptografada3, $cifra, $key[0], 0, $IV[0], $tag[0]);
                $mensagemCriptografada  = base64_decode($mensagemCriptografada2);
                $timeToDecrypt = microtime(true) - $inicio;

                break;
        }

        return $timeToDecrypt;
    }

    
    // Encrypts the data according to the method selected
    function encrypt($method, $data) {
       
        $timeToEncrypt = 0.0;
        $timeToDecrypt = 0.0;

        switch ($method) {
            
            case 1:

                $cifra = "aes-128-gcm";
                $key   = random_bytes(16);
                $IV    = random_bytes(openssl_cipher_iv_length($cifra));
                $tag   = "";
                
                $inicio  = microtime(true);
                $mensagemCriptografada  = base64_encode($data);
                $mensagemCriptografada2 = openssl_encrypt($mensagemCriptografada, $cifra, $key, 0, $IV, $tag);
                $timeToEncrypt = microtime(true) - $inicio;
                
                $timeToDecrypt = decrypt($method, $mensagemCriptografada2, $key, $IV, $tag);
                break;

            case 2:
                
                $cifra = "aes-128-gcm";
                $key   = random_bytes(16);
                $IV    = random_bytes(openssl_cipher_iv_length($cifra));
                $tag   = "";
                
                $cifra2 = "aes-192-gcm";
                $key2   = random_bytes(24);
                $IV2    = random_bytes(openssl_cipher_iv_length($cifra2));
                $tag2   = "";
                
                $inicio  = microtime(true);
                $mensagemCriptografada  = base64_encode($data);
                $mensagemCriptografada2 = openssl_encrypt($mensagemCriptografada, $cifra, $key, 0, $IV, $tag);
                $mensagemCriptografada3 = openssl_encrypt($mensagemCriptografada2, $cifra2, $key2, 0, $IV2, $tag2);
                $timeToEncrypt = microtime(true) - $inicio;
                
                $timeToDecrypt = decrypt($method, $mensagemCriptografada3, [$key, $key2], [$IV, $IV2], [$tag, $tag2]);
                break;

            case 3:
            
                $cifra = "aes-128-gcm";
                $key   = random_bytes(16);
                $IV    = random_bytes(openssl_cipher_iv_length($cifra));
                $tag   = "";
                
                $cifra2 = "aes-192-gcm";
                $key2   = random_bytes(24);
                $IV2    = random_bytes(openssl_cipher_iv_length($cifra2));
                $tag2   = "";
                
                $cifra3 = "aes-256-gcm";
                $key3   = random_bytes(32);
                $IV3    = random_bytes(openssl_cipher_iv_length($cifra3));
                $tag3    = "";
                
                $inicio  = microtime(true);
                $mensagemCriptografada  = base64_encode($data);
                $mensagemCriptografada2 = openssl_encrypt($mensagemCriptografada, $cifra, $key, 0, $IV, $tag);
                $mensagemCriptografada3 = openssl_encrypt($mensagemCriptografada2, $cifra2, $key2, 0, $IV2, $tag2);
                $mensagemCriptografada4 = openssl_encrypt($mensagemCriptografada3, $cifra3, $key3, 0, $IV3, $tag3);
                $timeToEncrypt = microtime(true) - $inicio;
                
                $timeToDecrypt = decrypt($method, $mensagemCriptografada4, [$key, $key2, $key3], [$IV, $IV2, $IV3], [$tag, $tag2, $tag3]);
                break;

            case 4:

                $cifra = "aes-128-gcm";
                $key   = random_bytes(16);
                $IV    = random_bytes(openssl_cipher_iv_length($cifra));
                $tag   = "";
                
                $cifra2 = "aes-192-gcm";
                $key2   = random_bytes(24);
                $IV2    = random_bytes(openssl_cipher_iv_length($cifra2));
                $tag2   = "";
                
                $cifra3 = "aes-256-gcm";
                $key3   = random_bytes(32);
                $IV3    = random_bytes(openssl_cipher_iv_length($cifra3));
                $tag3    = "";

                $cifra4 = "aria-256-cbc";
                $key4   = random_bytes(32);
                $IV4    = random_bytes(openssl_cipher_iv_length($cifra4));
                $tag4    = "";
                
                $inicio  = microtime(true);
                $mensagemCriptografada  = base64_encode($data);
                $mensagemCriptografada2 = openssl_encrypt($mensagemCriptografada, $cifra, $key, 0, $IV, $tag);
                $mensagemCriptografada3 = openssl_encrypt($mensagemCriptografada2, $cifra2, $key2, 0, $IV2, $tag2);
                $mensagemCriptografada4 = openssl_encrypt($mensagemCriptografada3, $cifra3, $key3, 0, $IV3, $tag3);
                $mensagemCriptografada5 = openssl_encrypt($mensagemCriptografada4, $cifra4, $key4, 0, $IV4, $tag4);
                $timeToEncrypt = (microtime(true) - $inicio) * 1000000;
                
                $timeToDecrypt = decrypt($method, $mensagemCriptografada5, [$key, $key2, $key3, $key4], [$IV, $IV2, $IV3, $IV4], [$tag, $tag2, $tag3, $tag4]);
                break;
        }

        return [$timeToEncrypt, $timeToDecrypt];
    }
?>
