# BPI Test

The first half an hour was building a plan and doing research. Documentation
pages from the last hour of my browser history include:

- https://symfony.com/doc/current/components/workflow.html
- https://symfony.com/doc/current/components/console/single_command_tool.html
- https://symfony.com/doc/current/console.html

## Assumptions

- The document did not specify whether the application was meant to deal with
  binary strings, or strings containing the characters 1 and 0.
  I've opted to use strings containing the characters 1 and 0 because it's
  harder for the user to input a binary string should the desired inputs
  byte-length not be divisible by 8.
- This application is fault-tolerant, it will ignore any input character that is
  not 1 or 0.
- Couldn't think of a better name for the application than _automata_.

## Prerequisites

- PHP
- [Composer](https://getcomposer.org)
- Run the command `composer install` inside the project root directory.

## Usage

Pipe input to the application using standard input:

```bash
cd <project-root>
echo '100001111010101' | ./automata
```

To run unit tests:

```bash
./vendor/bin/phpunit
```
