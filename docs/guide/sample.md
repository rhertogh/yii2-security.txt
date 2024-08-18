Sample security.txt
===================
Minimal configuration:
```txt
Contact: admin@example.com

Canonical: https://example.com/.well-known/security.txt

Expires: 2024-08-19T00:00:00+00:00
```

Full functionality:
```txt
-----BEGIN PGP SIGNED MESSAGE-----
Hash: SHA512

# This is a header comment.
# It's included at the beginning of the security.txt file.

Policy: https://example.com/report-policy

Contact: admin@example.com
Contact: https://example.com/report

PreferredLanguages: en

Encryption: dns:5d2d37ab76d47d36._openpgpkey.example.com?type=OPENPGPKEY

# Hall of Fame
Acknowledgments: https://example.com/security-acknowledgments

# Want to join our awesome team?
Hiring: https://example.com/jobs

Canonical: https://example.com/.well-known/security.txt

Expires: 2024-08-19T00:00:00+00:00

# This is a footer comment.
# It's included at the end of the security.txt file.
-----BEGIN PGP SIGNATURE-----

iQGzBAEBCgAdFiEEhlpeTlh/ToJKqs0pAqkXZ/QD7FEFAmbCT4QACgkQAqkXZ/QD
7FEexAv+LaaTlCyszhihozexPOx+5NTgkh3RezGyoIlJLf+Ye1HJckm+FeXOp44p
maB3IKKL2RO4J/6cGUwIeMEZZBWGieu34Z49Lb6PGJv89uIi1S0W5P6yuzmGv3AB
TcK2jQ05fvXIQYQZbptq4zBvTOt7KUPAgKc29JkIzbroymxva1mUovHMhH4pp8Bj
gGhZCAxOvHdFFNHHiL3WnNQRq0QvTYdfiY+lTO0Rr+xNPyqyFIDlk/XLlVI7ILAl
odO6aHkun/Uuzt0oHRgASX6ej+Isxw4rOr3TV4Iw162QHiMhww0J3wCSEvDEb/Bo
Lzv0i1TgZ3WtRHqI6eyGtmklfOaHbA6ItOagr5W7Wns0RAdx/dDGYMmB8PH3ok3s
FB8DFCqXw9ZNgs8MeEh+HrvwxPi3C7IxHTSqX2NAsXesggAUmVng/aAuge8pSK4X
95DzhRSEP/jHMyPxn7BrxQ77Q4WJJ+IDKs/GQ936MokBQZNI8Bvfm+0JkRiJ+AV+
MetwN2JL
=tL/s
-----END PGP SIGNATURE-----
```
