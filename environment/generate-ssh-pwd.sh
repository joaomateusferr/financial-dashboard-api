#!/bin/bash

# Generate a 30 character password with uppercase, lowercase letters, numbers, and symbols
PWD=$(< /dev/urandom tr -dc 'A-Za-z0-9-().&@?#,/;+' | head -c30)

echo "Your secure password is: $PWD"