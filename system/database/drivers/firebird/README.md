# Firebird for Codeigniter

This is a Firebird driver for Codeigniter 2.0 +

Tested on Firebird 2.1 and Codeingniter 2.0

# Utilization mode / Modo de utilización

## Example / Ejemplo

Put the files in the "system/database/drivers/firebird" folder and in the "application/config/database.php" you can use a configuration like this:
```php
    $db['default']['hostname'] = "localhost";
    $db['default']['username'] = "SYSDBA";
    $db['default']['password'] = "masterkey";
    $db['default']['database'] = "databasealias"; //or "c:\mydatabase.fdb"
    $db['default']['dbdriver'] = "firebird";
```

you can provide feedback in the discussion thread here:

http://codeigniter.com/forums/viewthread/171008/

Good Luck

Carlos GT
