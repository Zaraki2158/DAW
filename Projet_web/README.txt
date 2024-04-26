IMPORTANT

Pour pouvoir upload dans les cours des fichiers volumineux, ouvrez votre fichier php.ini (le mien était à cet emplacement wamp/bin/apache/apache2.4.58/bin/php.ini, le vôtre doit être à peu près au même), et modifier ces deux lignes : 
upload_max_size = 2M
post_max_size = 8M
Elles sont configurées avec une limite très faible par défaut, remplacez 2M et 8M par 1G pour être large.