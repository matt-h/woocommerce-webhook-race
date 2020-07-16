# WooCommerce order.updated race condition.
When changing order status in a woo order, when the webhook `order.update` fires it is possible that it fires with the old status information.

This is similar to the bug details in https://github.com/woocommerce/woocommerce/issues/26787 except that it also happens with Async webhooks as well.

The difference here is that because it is async it doesn't happen all of the time but it is a race condition depending if the webhook fires with the action scheduler before or after the status change has happened.

## Setup your Test WordPress Environment

wp-cli, php, and mysql is required to run this.

First clone this repository and cd into it.
```
git clone git@github.com:matt-h/woocommerce-webhook-race.git
cd woocommerce-webhook-race
```

Either run `./setup.sh` as is or manually run the commands below (changing the config commands as needed).
```
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
```

# Running the tests.

Start up 3 terminals in the `woocommerce-webhook-race` directory and run the following 3 scripts, one in each.

Php server which listens for the webhooks.
```
php -S localhost:8080 webhook-listener.php
```

Runs the WooCommerce Action Scheduler continuously using wp-cli
```
./action-scheduler.sh
```

Sets up the test environment, creates a webhook, and creates test orders until the race condition is detected.
```
php create-orders.php
```

Watch the `php create-orders.php` script and it should output and stop when it has detected an order that triggered the race condition.

# See the results
Check the `pending.log` and it should contain one webhook result and show a status of `pending` instead of `processing`.

Check the `all_webhooks.log` and it should contain show the same order from `pending.log` with the status of `pending` and have no webhooks sent for that order which contain the status of `processing`
