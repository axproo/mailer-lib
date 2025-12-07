# 📧 Axproo Mailer

Une librairie PHP basée sur **CodeIgniter 4** permettant d’envoyer facilement des e-mails HTML,  
avec **templates dynamiques**, **pièces jointes** et **injection de variables**.

---

## 🚀 Installation

Dans ton projet CodeIgniter :

```bash
composer require axproo/mailer-lib
```

ou, si tu l’as en local :

```bash
cd app/Libraries/MailerLib
composer dump-autoload -o
```

## ⚙️ Utilisation simple

Exemple d’envoi d’un mail

```php
use Axproo\Mailer\Services\MailerService;

$mailer = new MailerService();

$mailer->send(
    'contact@example.com',
    'Bienvenue sur notre plateforme',
    'emails/welcome',
    [
        'username' => 'Christian',
        'message' => 'Merci de rejoindre AXPROO 🚀'
    ]
);
```

## ⚙️ Utilisation directe dans ton projet

Executer:

```bash
composer dump-autoload -o
```

Après autoload, tu peux envoyer des e-mails depuis n’importe où dans ton application CodeIgniter 4 sans importer la classe :

```php
mailer()->send(
    'contact@exemple.com',
    'Bienvenue sur Axproo 🎉',
    'emails/welcome',
    [
        'username' => 'Christian',
        'message' => 'Merci d’avoir rejoint notre plateforme.'
    ]
);
```

## 🧩 Exemple de template

Fichier : app/Views/emails/welcome.php

```html
<html>
  <body>
    <div style="text-align:center;">
      <img src="<?= esc($image) ?>" alt="Header" width="200"><br>
      <h2>Bonjour <?= esc($username) ?> 👋</h2>
      <p><?= esc($message) ?></p>
      <footer>
        <p>&copy; <?= esc($year) ?> AXPROO. Tous droits réservés.</p>
      </footer>
    </div>
  </body>
</html>
```

## 🛠️ Configuration SMTP

Dans ton fichier .env ou /config/email.php:

```bash
email.protocol = smtp
email.SMTPHost = smtp.gmail.com
email.SMTPUser = you@example.com
email.SMTPPass = your-password
email.SMTPPort = 587 ou 465
email.SMTPCrypto = tls ou ssl
email.mailType = html
```

## 📦 Caractéristiques

- ✅ Compatible avec CodeIgniter 4.x
- ✅ Support des templates dynamiques
- ✅ Gestion automatique du CID pour les images
- ✅ Log des erreurs SMTP
- ✅ PSR-4 et Composer-ready

## 🧑‍💻 Auteur

👤 Christian Djomou
📧 [contact@axproo.com](christian@axproo.com)
🌐 [https://axproo.com](https://www.axproo.com)
Licence : MIT
