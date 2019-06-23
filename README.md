# PicWeb

Created in 2009 by Andrew Landsverk as a learning exercise.

## Setup

### Docker

1. Edit `picweb.sql` and update the admin credentials to be your own (Passwords need to only be MD5 hashed)
2. docker-compose up -d --build
3. You can now navigate to http://localhost/
4. Done!

### LAMP

Full instructions are not provided for LAMP stacks at this time. You will need to edit a few files to point to a different MySQL instance however:

- `picweb/config.php`
