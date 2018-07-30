# Migration Project

This project was made to facilitate migrations in mysql databases, helping with the agility of the process and having greater control over bank versioning.

# Avaliable commands:

## begin: 
Create the migration control table.


## create <migration_name>: 
Create a php file to be filled with your DB alterations.


## migrate <migration_token>: 
Execute the migrations fowards or backwards, depending on the given token.

Optional: fake: Fakes the execution for this token and save the pointer to him.
Usage example: `php migration.php migrate 02 fake`

# Before start:
You need to create a file named: `db_config.json`.
This file should follow the example of `db_config_example.json`.

#### author: André Gaspar