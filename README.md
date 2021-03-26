# My Host

WooCommerce library to return the hosting provider of the server the code is run on.

i.e. to show particular documentation to users based on their host.

## Use

Sign up for [ipinfo.io](https://ipinfo.io) at [ipinfo.io/signup](https://ipinfo.io/signup).

In `wp-config.php` add:

```php
define( 'IPINFO_API_TOKEN', '12ab34cd56ef' );
```

```php

$sut = new BrianHenryIE\WC_My_Host\My_Host();

$result = $sut->get_host_provider();
```

`$result` is an optional string, the hosting provider if known, null otherwise.

## Practical

This was something I threw together one afternoon, I'm not even using it in production.

This was the solution I came up with after trying to run `traceroute` on servers, somewhat unsuccessfully. 

Presumably, if you want to use this, you have a list of web-hosts in mind – you'll need to compile a list of hosts' ASN names you wish to target.

Not all web-hosts' ASNs obviously correspond, e.g. SiteGround's servers' ANSs resolve to Google because SiteGround use Google as their host.

## Install

You should probably use [Mozart](https://github.com/coenjacobs/mozart/) and include this with Composer:

```json
 "repositories": [
 {
  "url": "https://github.com/BrianHenryIE/bh-wc-my-host",
  "type": "git"
 }

 "require": {
  "brianhenryie/wc-my-host": "*"
 }
```

## Contribute

To add to the hosts' list, find your server IP by running `wp shell` then: 

```
\WC_Geolocation::get_external_ip_address();
```

Lookup the IP address on [ipinfo.io](https://ipinfo.io/)

## Test

Add a `.env.secret` file in the project root with 

```
IPINFO_API_TOKEN=12ab34cd56ef
```