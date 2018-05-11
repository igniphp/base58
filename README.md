# ![Igni logo](https://github.com/igniphp/common/blob/master/logo/full.svg)
[![Build Status](https://travis-ci.org/igniphp/base58.svg?branch=master)](https://travis-ci.org/igniphp/base58)

## About

PHP Base58 implementation used to represent large integers as alphanumeric text.

### Installation
```composer require igniphp/base58```

### Usage Igni\Crypto\Base58

#### `encode(string|int $string): string`
Encodes passed integer|string into base58 representation.

#### `decode(string $string): string`
Decodes base58 representation of big integer into string.
