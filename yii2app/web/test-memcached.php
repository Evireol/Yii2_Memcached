<?php
echo "Memcached class exists: " . (class_exists('Memcached') ? 'YES' : 'NO') . "\n";
echo "Extension loaded: " . (extension_loaded('memcached') ? 'YES' : 'NO') . "\n";

phpinfo()
?>