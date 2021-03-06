# Lighthouse Redis Broadcaster [![Build Status](https://travis-ci.org/thekonz/lighthouse-redis-broadcaster.svg?branch=master)](https://travis-ci.org/thekonz/lighthouse-redis-broadcaster)

[Lighthouse](https://lighthouse-php.com/) already supports pusher, but does not deliver its own redis based solution.
This package enables graphql subscriptions using presence channels of the [laravel-echo-server](https://github.com/tlaverdure/laravel-echo-server).

For a client solution, check out the [Apollo Lighthouse Subscription Link](https://github.com/thekonz/apollo-lighthouse-subscription-link).

## Installation

_I assume that you already have [Lighthouse](https://lighthouse-php.com/) and [laravel-echo-server](https://github.com/tlaverdure/laravel-echo-server) installed. If not, please check out their installation steps before continuing._

Install the package with composer:

```bash
composer require thekonz/lighthouse-redis-broadcaster
```

Add the service provider **after** the Lighthouse subscription service provider in the `config/app.php`:

```php
        /*
         * Package Service Providers...
         */
        \Nuwave\Lighthouse\Subscriptions\SubscriptionServiceProvider::class,
        \thekonz\LighthouseRedisBroadcaster\SubscriptionServiceProvider::class,
```

Add this to your `.env`:

```dotenv
LIGHTHOUSE_BROADCASTER=redis
REDIS_PREFIX=
```

If you do not set the `REDIS_PREFIX` to empty, it will default to `<app name>_database_` (by default: `laravel_database_`) and all redis channels will be prefixed with it.

## Setting up automatic removal of subscription channels

Lighthouse by default does not remove vacated channels. In order to prevent redis from running low on memory all the time, you need to configure the laravel-echo-server to publish updates about its presence channels and run a subscriber that removes vacated channels from redis.

Enable presence channel updates in your `laravel-echo-server.json` by setting `publishPresence` to `true`:

```json
  "databaseConfig": {
    ...
    "publishPresence": true
  }
```

Run the subscription command to remove vacated channels:

```bash
php artisan lighthouse:subscribe
```

## Usage

_If you are using Apollo, you should use the [Apollo Lighthouse Subscription Link](https://github.com/thekonz/apollo-lighthouse-subscription-link)._

Create a subscription as described in the [Lighthouse docs](https://lighthouse-php.com/4.12/subscriptions/defining-fields.html). For the purpose of demonstration, I assume the subscription is `postUpdated` like in the docs.

Now query the api:

```graphql
subscription test {
  postUpdated {
    id
    title
  }
}
```

The response will be:

```json
{
  "data": {
    "postUpdated": null
  },
  "extensions": {
    "lighthouse_subscriptions": {
      "version": 1,
      "channels": {
        "test": "private-lighthouse-9RrjQE84nqaxXt58ZsgREPaI9AxGjAv4-1588101712"
      }
    }
  }
}
```

Now you may use laravel echo to monitor the subscription as a presence channel:

```js
Echo.join(
  "private-lighthouse-9RrjQE84nqaxXt58ZsgREPaI9AxGjAv4-1588101712"
).listen(".lighthouse.subscription", ({ channel, data }) => {
  console.log(channel); // private-lighthouse-9RrjQE84nqaxXt58ZsgREPaI9AxGjAv4-1588101712
  console.log(data); // { postUpdated: { id: 1, title: "New title" } }
});
```

## Contributing and issues

Feel free to contribute to this package using the issue system and pull requests on the `develop` branch.

Automated unit tests must be added or changed to cover your changes or reproduce bugs.
