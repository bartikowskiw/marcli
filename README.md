# MARC CLI Tools

Command line tool collection for MARC record files.

## Make Marcli globally executable

Making Marcli globally executable makes living easier. The simplest way
is to create a shell script in ```~/bin```.

```sh
#!/bin/bash
php /path/to/marcli.php "$@"
```

Moving the phar archive to ~/bin is also an simple option.

## Basic MARC functions

```sh
# Echo a colored dump of the records
marcli marc:dump random.mrc

# Echo the number of record inside the file
marcli marc:count random.mrc
```

## Using stdio & pipes

```sh
# Same as marcli marc:count random.mrc
cat random.mrc | marcli marc:count

# Chainable with the --raw option:
cat random.mrc | marcli marc:find --raw needle | marcli marc:count

# Save the result to a new MARC file:
cat random.mrc | marcli marc:find --raw needle > needle.mrc
```

## Lookup table

MARC files can get quite large. This function creates a SQLite database
where the id (just the numeric part) in the 001 field and the position
of the corresponding record in the file are saved. This makes searching
for a records with a given id fast.

```sh
marcli map:write random.mrc db.sqlite
marcli map:read 123456 random.mrc db.sqlite
```
