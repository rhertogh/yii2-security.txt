Frequently Asked Questions
==========================

This FAQ describes common questions and errors and provides possible solutions for them.

Common errors
-------------

### Missing driver
**Error**:  
Either the Crypt_GPG package (https://packagist.org/packages/pear/crypt_gpg) or the "gnupg" extension (https://www.php.net/manual/en/book.gnupg.php) must be installed.  
**Solution**:  
Install the Crypt_GPG package
```bash
composer require pear/crypt_gpg
```

### Missing binary
**Error**:  
GPG binary not found. If you are sure the GPG binary is installed, please specify the location of the GPG binary using the 'binary' driver option  
**Solution**:  
Install the development version of [GnuPG Made Easy (GPGME)](https://gnupg.org/software/gpgme/index.html) on the server.
