#!/usr/bin/env bash

set -e

# Clone WooCommerce Core
git clone git@github.com:woocommerce/woocommerce.git

# Make WordPress directory
mkdir wordpress
cd wordpress

# Install WordPress
wp core download

# Create WP Config.
wp config create --dbname=woocommerce_webhook --dbuser=root --dbpass=

# Install Wordpress.
wp core install --url=http://localhost --title="WooCommerce Webhook" --admin_user=admin --admin_email=admin@example.com

# Install WooCommerce
wp plugin install --activate woocommerce

# Create mu-plugin directory
mkdir -p wp-content/mu-plugins
