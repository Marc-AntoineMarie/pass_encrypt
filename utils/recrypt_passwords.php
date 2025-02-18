<?php
require_once 'encryption.php';

// Liste des variables d'environnement à chiffrer
$env_vars = [
    'LDAP_ADMIN_PASSWORD',
    'LDAP_SSH_PASSWORD'
];

// Chiffrer les mots de passe depuis l'environnement
$encrypted = [];
foreach ($env_vars as $key) {
    $value = getenv($key);
    if (!$value) {
        echo "ATTENTION: $key n'est pas définie dans l'environnement\n";
        continue;
    }
    $encrypted[$key] = 'ENC:' . Encryption::encrypt($value);
}

if (empty($encrypted)) {
    die("Aucune variable d'environnement à chiffrer n'a été trouvée.\n");
}

// Générer le contenu du fichier .env
$envContent = '';
foreach ($encrypted as $key => $value) {
    $envContent .= "$key=$value\n";
}

// Chemin vers le fichier .env
$envPath = __DIR__ . '/../config/.env';

// Vérifier si le dossier config existe
$configDir = dirname($envPath);
if (!is_dir($configDir)) {
    if (!mkdir($configDir, 0755, true)) {
        die("Impossible de créer le dossier config\n");
    }
}

// Écrire dans le fichier .env
if (file_put_contents($envPath, $envContent)) {
    echo "Variables d'environnement chiffrées avec succès :\n\n";
    echo $envContent;
} else {
    die("Erreur lors de l'écriture du fichier .env\n");
}
