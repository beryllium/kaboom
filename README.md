Kaboom
======

Author: Kevin Boyd (https://whateverthing.com)

Contributors:

* [Kevin Boyd](https://github.com/beryllium)
* [Jeremy Kendall](https://github.com/jeremykendall)

License: MIT

Inspiration: A twitter joke.

> "Here's a useful library: One that detects whether or not you're in a dev environment and explodes if error reporting isn't -1 :-)"

https://twitter.com/JeremyKendall/status/420672420253822976

---

And redeveloped as part of a second twitter joke:

> "Here's a fun idea: Temporal Todos ..."

https://twitter.com/Beryllium9/status/1314780273398013952

> A code comment declares an urgent TODO task. Subsequently, an if statement uses the current unix timestamp (as of when it was coded) plus 1 day (in seconds) to detect if a RuntimeException should be thrown to enforce the TODO.

---

## How to use Kaboom

In its default configuration, Kaboom will "blow up", by throwing an exception, if the requirements are tripped.

Here, we set a Todo that will "blow up" starting on October 20th, 2020.

```php
$kaboom = new Kaboom();
$kaboom->todo(
    "Fix this code or you'll be sorry! KAB-200",
    "2020-10-31"
);
```

Alternatively, we can harken back to the original Kaboom implementation and "blow up" if error reporting is insufficient for our environment.

```php
$env = 'dev';
$kaboom = new Kaboom();
$kaboom->custom(
    "Error reporting is not set correctly!",
    fn() => strtolower($env) === 'dev' && error_reporting() !== -1
);
```

## Configuring a Custom Kaboom Behaviour

Kaboom supports various Handlers that control how it behaves when a condition is tripped.

### Logging

To configure Kaboom to log instead of throwing an exception, do this:

```php
$kaboom = new Beryllium\Kaboom\Kaboom(new Beryllium\Kaboom\Handlers\LoggingHandler($logger));
$kaboom->todo(
    "Fix this code or you'll be sorry! KAB-200",
    "2020-10-31"
);
```

This can get a little lengthy, which is where a Dependency Injection Container would help.

For example, you could configure `LoggingHandler` to be the default implementation of `HandlerInterface`, which would
then allow autowiring to wire things together so all you would have to request is
`$container->get(Beryllium\Kaboom\Kaboom::class)`.

### Null

You may want to have different configurations for different environments, such as not wanting to blow up on Production.

In that case, you can use the Null handler:

```php
$kaboom = new Beryllium\Kaboom\Kaboom(
    $env === 'prod' ? new Beryllium\Kaboom\Handlers\NullHandler() : new Beryllium\Kaboom\Handlers\ExceptionHandler()
);

$kaboom->todo(
    "Fix this code or you'll be sorry! KAB-200",
    "2020-10-31"
);
```

### Grouped

Perhaps you want to have multiple handlers, such as if you've written a custom Slack handler (please contribute it back
if so!! thanks!!). That's where the `GroupHandler` comes in.

```php
$kaboom = new Beryllium\Kaboom\Kaboom(
    new Beryllium\Kaboom\Handlers\GroupHandler([
        new Beryllium\Kaboom\Handlers\LoggingHandler(),
        new Your\Custom\Namespace\SlackHandler(),
    ])
);

$kaboom->todo(
    "Fix this code or you'll be sorry! KAB-200",
    "2020-10-31"
);
```

Now, Kaboom will loop through your provided handlers and log the message and then send it to Slack (in that order).

## Contributions Welcome

If you have ideas for making Kaboom more widely useful, please add Issues or PRs.

If you've found Kaboom to be useful in your projects, please let me know on Twitter!

[@beryllium9](https://twitter.com/beryllium9)

Thanks!