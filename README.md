# WP Rest Schema Builder

....

[![Latest Stable Version](http://poser.pugx.org/pinkcrab/wp-rest-schema/v)](https://packagist.org/packages/pinkcrab/wp-rest-schema) [![Total Downloads](http://poser.pugx.org/pinkcrab/wp-rest-schema/downloads)](https://packagist.org/packages/pinkcrab/wp-rest-schema) [![Latest Unstable Version](http://poser.pugx.org/pinkcrab/wp-rest-schema/v/unstable)](https://packagist.org/packages/pinkcrab/wp-rest-schema) [![License](http://poser.pugx.org/pinkcrab/wp-rest-schema/license)](https://packagist.org/packages/pinkcrab/wp-rest-schema) [![PHP Version Require](http://poser.pugx.org/pinkcrab/wp-rest-schema/require/php)](https://packagist.org/packages/pinkcrab/wp-rest-schema)
![GitHub contributors](https://img.shields.io/github/contributors/Pink-Crab/WP_Rest_Schema?label=Contributors)
![GitHub issues](https://img.shields.io/github/issues-raw/Pink-Crab/WP_Rest_Schema)
[![WordPress 5.9 Test Suite [PHP7.2-8.1]](https://github.com/Pink-Crab/WP_Rest_Schema/actions/workflows/WP_5_9.yaml/badge.svg)](https://github.com/Pink-Crab/WP_Rest_Schema/actions/workflows/WP_5_9.yaml)
[![WordPress 6.0 Test Suite [PHP7.2-8.1]](https://github.com/Pink-Crab/WP_Rest_Schema/actions/workflows/WP_6_0.yaml/badge.svg)](https://github.com/Pink-Crab/WP_Rest_Schema/actions/workflows/WP_6_0.yaml)
[![WordPress 6.1 Test Suite [PHP7.2-8.1]](https://github.com/Pink-Crab/WP_Rest_Schema/actions/workflows/WP_6_1.yaml/badge.svg?branch=master)](https://github.com/Pink-Crab/WP_Rest_Schema/actions/workflows/WP_6_1.yaml)
[![codecov](https://codecov.io/gh/Pink-Crab/WP_Rest_Schema/branch/master/graph/badge.svg?token=4yEceIaSFP)](https://codecov.io/gh/Pink-Crab/WP_Rest_Schema) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Pink-Crab/WP_Rest_Schema/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Pink-Crab/WP_Rest_Schema/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/c836fefdf060dd6f74c0/maintainability)](https://codeclimate.com/github/Pink-Crab/WP_Rest_Schema/maintainability)

## This is still pre release ##
> While this library is in pre release, it is still being used in production, but please be aware that the API may change. Current features are stable, but new features may be added.

****

## Why? ##

Defining valid JSON rest schema in WordPress is a little messy and easy to make a mistake with it being lots of nested arrays. This library attempts to make this process cleaner and simpler with a fully fluent, object driven interface.

****

## Setup ##

To install, you can use composer
```bash
$ composer require pinkcrab/wp-rest-schema-builder
```
for_meta_data
## Basic Usage ##

> This can be used for Register Post Meta
```php
register_post_meta( 'post', 'fixed_in', array(
    'type'         => 'string',
    'show_in_rest' => array(
        'single' => true,
        'schema' => Argument_Parser::for_meta_data(
            String_Type::on('fixed_in')
                ->min_length(10)
                ->max_length( 42 )
                ->required()
                ->description('This is a required string value, that must be between 10 and 42 chars long.')
        ),
    ),
) );
```
> Can also be used with the [Perique Registerable](https://github.com/Pink-Crab/Perique-Registerables) library
```php
    $meta_data = (new Meta_Data('fixed_in'))
        ->post_type('post')
        ->type('string')
        ->rest_schema(
            Argument_Parser::for_meta_data(
                String_Type::on( 'fixed_in' )
                    ->min_length(10)
                    ->max_length( 42 )
                    ->required()
                    ->description('This is a required string value, that must be between 10 and 42 chars long.')
            )
        );
```


The WP Rest Schema Builder can be used in various places where you would normally define a schema, such as Rest Routes, Registering Post Types, Taxonomies and Meta Data.

## Change Log ##
* 0.1.0 Inital version