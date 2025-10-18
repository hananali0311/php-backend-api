#!/bin/bash
set -e
rm -rf /var/www/html/*
mkdir -p /var/www/html
chown -R www-data:www-data /var/www/html
