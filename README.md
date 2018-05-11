# ![Igni logo](https://github.com/igniphp/common/blob/master/logo/full.svg)

## Igni\Util\Base58

PHP Base58 implementation used to represent large integers as alphanumeric text.

### API

#### `encode(string|int $string): string`
Encodes passed integer|string into base58 representation.

#### `decode(string $string): string`
Decodes base58 representation of big integer into string.
