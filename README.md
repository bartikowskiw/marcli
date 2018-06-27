# MARC CLI Tools

Command line tool collection for MARC record files.

## Installation

Marcli has its own Package in the [UMLTS Private Package Archives (PPA)](https://launchpad.net/~umlts/+archive/ubuntu/marcli). Installing Marcli under Ubuntu is fairly simple:

```sh
sudo add-apt-repository ppa:umlts/marcli
sudo apt-get update
sudo apt-get install marcli
```

### Use the phar archive

The phar archive is part of the [*release* branch of this repository](https://github.com/bartikowskiw/marcli/tree/release).
You can download the file there.

### Manual installation

Download Marcli via git:

```sh
git clone https://github.com/bartikowskiw/marcli.git
```

Change into the Marcli directory and get the dependencies:

```sh
cd marcli
composer --no-dev install
```

### Make Marcli globally executable

Making Marcli globally executable makes living easier. The simplest way
is to create a shell script in ```~/bin```.

```sh
#!/bin/bash
php /path/to/marcli.php "$@"
```

Moving the phar archive to ~/bin is also a simple option.

## Basic MARC functions

```sh
# Echo a colored dump of the records
marcli marc:dump random.mrc

# Echo the number of record inside the file
marcli marc:count random.mrc

```

## Find / Find & replace

```sh
marcli marc:find beef random.mrc
marcli marc:replace beef pork random.mrc
```

### Narrowing the search

The search can be narrowed by tag, subfield, and indicators

```sh
# Returns records with the needle "beef" in the MARC field 245 (which
# usually stores titles)
marcli marc:find --tag=245 beef random.mrc
```

### Leader

The leader has its own special tag "LDR" or "LEADER":

```sh
# Returns records with set "d" (delete) flag in the leader
marcli marc:find --tag=LDR "^.....d" random.mrc
```

### Regular expressions

The search accepts PCRE [regular expressions](https://secure.php.net/manual/en/reference.pcre.pattern.syntax.php).
This would look for record that contain the word "beef" or "pork" in any
field:

```sh
    marcli marc:find "(beef|pork)" random.mrc
```

### Search for duplicates

Looks for duplicate MARC records. Records are regarded as duplicate
if they share the 001 MARC field with other records. Marcli returns
all records with the same identifier.

```sh
marcli search:duplicates random.mrc
```

## Split record set

Split MARC files into chunks of a certain size.

```sh
# Split random.mrc into chunks of 1,000 records each
marcli marc:split 1000 random.mrc

# Split random.mrc and save the resulting files into another folder
marcli marc:split --output-dir=/tmp 1000 random.mrc

# Split random.mrc, use characters instead of numbers for enumeration
marcli marc:split --enum-type-chars 1000 random.mrc
```

## Boolean operations

```sh
# Returns records that exist in both MARC files (according to the id
# in the MARC 001 field).
marcli bool:add random.mrc random2.mrc

# Returns records that do exist in the first file but not in the second.
marcli bool:not random.mrc random2.mrc
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
where the id in the 001 field and the position of the corresponding
record in the file are saved. This makes searching for a records with a
given id fast.

```sh
marcli map:write random.mrc
marcli map:read 123456 random.mrc
```

The `map:read` command supports regular expressions. The `--regexp` 
option turns this feature on:

```sh
marcli map:read --regexp "12345[0-3]" random.mrc
```

This command searches for the ids 123450, 123451, 123452, and 123453.
Regular expressions should only be switched on if really needed. They
significantly slow the lookups down.