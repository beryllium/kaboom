Kaboom
======

Author: Kevin Boyd

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
    "2020-10-20"
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