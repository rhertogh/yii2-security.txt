Signing security.txt
====================

The Yii2 security.txt extension supports signing the generated output.
Please see [RFC 9116 - Digital Signature](https://www.rfc-editor.org/rfc/rfc9116#name-digital-signature) for more information.

Prerequisites
-------------
The Yii2 security.txt extension uses the [GNU Privacy Guard (GnuPG)](https://gnupg.org/) 
to generate the [OpenPGP](https://www.ietf.org/rfc/rfc4880.html) signature.  
You must have an ASCII-armored PGP private key to configure Yii2 security.txt extension
and distribute the PGP public key so that the signature can be verified.

Although the details of key management are outside the scope of this documentation, here is quick guide: 

1. To generate a new key, run:
   ```bash
   gpg --full-generate-key
   ```
   Follow the prompt, recomended options are:
   1. Please select what kind of key you want:
      `1` RSA and RSA (default)
   2. What key size do you want?
      `3072` (default)
   3. Please specify how long the key should be valid.
      `0` key does not expire (default)
   4. Real name:
      `security.txt`
   5. Email address:
      `security.txt@your-domain.com` (note: this doesn't have to be a real email address, it's just used to identify the signer)
   6. Comment:
      `identity for signing security.txt file`
   7. Confirm:
      `o`
   8. Please enter the passphrase:
      DO NOT USE A PASSPHRASE!
      *leave blank*
   9. You have not entered a passphrase:
      `<Yes, protection is not needed>`
   10. Repeat empty passphrase

2. Export your private key, use the same email address as used in step 1.5: 
   ```bash
   gpg --armor --export-secret-key security.txt@your-domain.com
   ```

   The output should look like:
   ```txt
   -----BEGIN PGP PRIVATE KEY BLOCK-----
   
   lQVYBGa6h2cBDADBqrPHnx3FV7uuESHMh6gcSSOLnimgyFTdfRy6yjaEfbnAoW/S
   ... MULTIPLE OTHER LINES ...
   dcOTLoGUMcK0OfFrkhsOVUlOTUhi/WT0M8GP2SHs63bgA3Jslnuz
   =3Uin
   -----END PGP PRIVATE KEY BLOCK-----
   ```
   
   Copy the private key and expose it in your application as an environment variable, e.g. `YII2_SECURITY_TXT_PGP_PRIVATE_KEY`.

   > [!HINT]
     In some cases (e.g. when using a `.env` file) it might be needed to replace newline characters with the literal string `\n`.  
     E.g. `YII2_SECURITY_TXT_PGP_PRIVATE_KEY="-----BEGIN PGP PRIVATE KEY BLOCK-----\n\nlQVYBGa6h2cBDADBqrP..."`.
   
3. Export your public key, use the same email address as used in step 1.5:
   ```bash
   gpg --armor --export security.txt@your-domain.com
   ```
   
   The output should look like:
   ```txt
   -----BEGIN PGP PUBLIC KEY BLOCK-----

   mQGNBGbCQIEBDADPT9P/v1xnU5LF4Z8cmDFO4DYga7YsC8C/iOs2dGb8wPCswKHr
   ... MULTIPLE OTHER LINES ...
   Bhf9BB4dKQXYKF/bMQ==
   =Aj4V
   -----END PGP PUBLIC KEY BLOCK-----
   ```
   
   See [Public key distribution](#public-key-distribution) below for more info on how to distribute your public key. 

Installation
------------

1. Install the development version of [GnuPG Made Easy (GPGME)](https://gnupg.org/software/gpgme/index.html) on the server.  
   Note: The exact way to install GPGME is operating system depended.
   - On Ubuntu or Debian:
     ```bash
     sudo apt-get -y install libgpgme-dev
     ```
   - On CentOS or Fedora:
     ```bash
     sudo yum install gpgme-devel
     ```
   - On macOS:
     ```bash
     brew install gpgme
     ```
     
2. Ensure the GnuPG home directory is writable.  
   The GnuPG 'homedir' needs to be writable for the user under which the webserver process is running (e.g. www-data).
   By default, the GnuPG 'homedir' is `~/.gnupg` (once again, of the user under which the webserver process is running).
   
   Create GnuPG 'homedir' and make it writable (assuming the webserver user is `www-data` and that user's homedir is `/var/www/`) 
   ```bash
   mkdir -p /var/www/.gnupg
   chown www-data:www-data /var/www/.gnupg
   ```
   > [!TIP]
   If you don't want to use the default GnuPG home directory, you can override it via the `GNUPGHOME` environment variable.

3. To bridge PHP with GPGME the Yii2 security.txt extension supports two different options:  
   > [!TIP]
     Only one of these libraries need to be installed.    
 
   1. *Recommended* The Crypt_GPG package (https://packagist.org/packages/pear/crypt_gpg).
      To install the latest stable version of crypt_gpg via composer you can run:
      ```bash
      composer require pear/crypt_gpg
      ```
      
   2. The "gnupg" extension (https://www.php.net/manual/en/book.gnupg.php).

Configuration
-------------

Once the dependencies are installed, simply specify the ASCII-armored PGP private key in `pgpPrivateKey`.
In the sample below the key is stored in the `YII2_SECURITY_TXT_PGP_PRIVATE_KEY` environment variable.
```php title="Appplication Configuration (sample/config/main.php)"
return [
    'bootstrap' => [
        'security.txt',
        // ...
    ],
    'modules' => [
        'security.txt' => [
            'class' => rhertogh\Yii2SecurityTxt\SecurityTxtModule::class,
            'contact' => 'admin@example.com',
            // ... other security.txt settings
            
            // Specify the ASCII-armored PGP private key
            'pgpPrivateKey' => getenv('YII2_SECURITY_TXT_PGP_PRIVATE_KEY'),
        ],
    ],
```

Public key distribution
-----------------------

The distribution of the PGP public key (by which security researches can verify the integrity of the security.txt file)
is outside the scope of this extension. A recommended way is to publish the key via DNS protected by DNSSEC.  
A helpful guide can be found at https://weberblog.net/pgp-key-distribution-via-dnssec-openpgpkey/.
