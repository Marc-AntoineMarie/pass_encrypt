# Utilitaires de Chiffrement

Ce module fournit des outils pour le chiffrement/déchiffrement sécurisé de données sensibles en PHP.

## Fonctionnalités

- Chiffrement AES-256-CBC avec IV unique
- Interface web pour tester le chiffrement
- Utilitaires en ligne de commande
- Gestion sécurisée des variables d'environnement

## Installation

1. Copiez `.env.example` vers `.env` :
```bash
cp .env.example .env
```

2. Générez une nouvelle clé de chiffrement :
```bash
openssl rand -base64 32
```

3. Mettez à jour le fichier `.env` avec votre nouvelle clé :
```
ENCRYPTION_KEY=votre_clé_générée
```

## Utilisation

### Interface Web
Accédez à `encrypt_form.php` pour tester le chiffrement via une interface web.

### Ligne de Commande
Pour chiffrer un mot de passe :
```bash
php encrypt_password.php "mon_mot_de_passe"
```

Pour rechiffrer les variables d'environnement :
```bash
php recrypt_passwords.php
```

## Sécurité

- Ne stockez JAMAIS la clé de chiffrement dans le code
- Utilisez toujours des variables d'environnement pour les données sensibles
- Gardez votre fichier `.env` hors du contrôle de version
- Utilisez une clé de chiffrement d'au moins 32 caractères

## Contribution

Les contributions sont les bienvenues ! Assurez-vous de :
1. Ne pas commiter de données sensibles
2. Ne pas commiter le fichier `.env`
3. Mettre à jour `.env.example` si vous ajoutez de nouvelles variables

## Licence

MIT
