Kaboom
======

Author: Kevin Boyd (https://whateverthing.com)

Contributors:

* [Kevin Boyd](https://github.com/beryllium)
* [Jeremy Kendall](https://github.com/jeremykendall)

License: MIT

## What Is Kaboom?

Kaboom helps you deal with the realities of coding in long-term projects.

It provides an interface for adding temporary code to projects, code that needs
to either start or stop running after a predetermined date on the calendar, or
safety protections based on environment conditions.

### ... okay then, WHY Is Kaboom?

In my career, I've encountered a number of scenarios that Kaboom can help with,
but the main one always seems to be forgetfulness. Organizations often don't
take the time to circle back and clean things up, unless it is specifically
called out in some way. It's often left up to individuals to remember to return
to some arbitrary old project and spruce it up.

By adding Kaboom-backed tripwires to your project, you can schedule future
reminders that are emitted by the code itself - directly into your logging and
reporting systems!

You can also customize the tripwire behaviour. Maybe instead of logging after a
set calendar date, you would prefer to detect an environment condition and throw
an exception. This was the original use case for Kaboom, inspired by a joke on
an ancient social media website called 'Twitter' from developer Jeremy Kendall.

> "Here's a useful library: One that detects whether or not you're in a dev
> environment and explodes if error reporting isn't -1 :-)"

https://twitter.com/JeremyKendall/status/420672420253822976

---

After a while, another idea arose:

> "Here's a fun idea: Temporal Todos ..."

https://twitter.com/Beryllium9/status/1314780273398013952

> A code comment declares an urgent TODO task. Subsequently, an if statement
> uses the current unix timestamp (as of when it was coded) plus 1 day (in
> seconds) to detect if a RuntimeException should be thrown to enforce the TODO.

---

And then I thought, why not fold that functionality into Kaboom, and use it to
help codebases fight back against cruft?

## How to use Kaboom

Kaboom's default behaviour is to throw a KaboomException if conditions are met.

This is because the `ExceptionHandler` class is used in the default constructor.

**`ExceptionHandler` - Example 1:**

Here, we set a message that will "go kaboom" starting on Oct 20, 2020.

```php
$kaboom = new Kaboom();
$kaboom->afterMessage(
    "2020-10-20",
    "Fix this code or you'll be sorry! KAB-200"
);
```

**`ExceptionHandler` - Example 2:**

Alternatively, we can harken back to the original Kaboom inspiration and
"go kaboom" if error reporting is insufficient for our environment.

```php
$env = 'dev';
$kaboom = new Kaboom();
$kaboom->condition(
    fn () => strtolower($env) === 'dev' && error_reporting() !== -1,
    fn () => "Error reporting is not set correctly!",
);
```

### Configuring a Custom Kaboom Handler

Kaboom supports various Handlers that control how it behaves when a condition is
tripped.

#### LoggingHandler

**`LoggingHandler` - Example 3:**

To configure Kaboom to log instead of throwing an exception, do this:

```php
use Beryllium\Kaboom\Kaboom;
use Beryllium\Kaboom\Handlers\LoggingHandler;
use Psr\Log\LogLevel;

$kaboom = new Kaboom(
    new LoggingHandler($logger, LogLevel::ERROR)
);

$kaboom->afterMessage(
    "2020-10-31",
    "Fix this code or you'll be sorry! KAB-200"
);
```

This can get a little lengthy, which is where a Dependency Injection Container
would help.

For example, you could configure `LoggingHandler` to be the default
implementation of `HandlerInterface`, which would then allow autowiring to wire
things together so all you would have to request is
`$container->get(Beryllium\Kaboom\Kaboom::class)`.

**NOTE:** The second parameter of `LoggingHandler`, the Log Level, is optional.
          The default log level is `WARNING`.

#### Null Handler

**`NullHandler` - Example 4:**

You may want to have different configurations for different environments, such
as not wanting to blow up on Production.

In that case, you can use the Null handler:

```php
use Beryllium\Kaboom\Kaboom;
use Beryllium\Kaboom\Handlers\NullHandler;
use Beryllium\Kaboom\Handlers\ExceptionHandler;

$kaboom = new Kaboom(
    $env === 'prod' ? new NullHandler() : new ExceptionHandler()
);

$kaboom->afterMessage(
    "2020-10-31",
    "Fix this code or you'll be sorry! KAB-200"
);
```

#### Grouped

Perhaps you want to have multiple handlers, such as if you've written a custom
Slack handler (please contribute it back if so!! thanks!!). That's where the
`GroupHandler` comes in.

**`GroupHandler` - Example 5:**

```php
use Beryllium\Kaboom\Kaboom;
use Beryllium\Kaboom\Handlers\GroupHandler;
use Beryllium\Kaboom\Handlers\LoggingHandler;
use Your\Custom\Namespace\SlackHandler;

$kaboom = new Kaboom(
    new GroupHandler([
        new LoggingHandler(),
        new SlackHandler(),
    ])
);

$kaboom->afterMessage(
    "2020-10-31",
    "Fix this code or you'll be sorry! KAB-200"
);
```

Now, Kaboom will loop through your provided handlers and log the message and
then send it to Slack (in that order).

## ... But Why?

You might look at the implementation and think, well, this is just an `if`
condition. And yeah, you're right - but it's a tiny bit more than that.

Kaboom establishes intentionality. When you use it to wrap something, you're
making a statement. You're saying that this `if` condition is special.

Maybe you're saying that the code is temporary and can be deleted at some point.

Maybe you're saying that it's an important safety protection, but you want to
be able to easily control Production vs Development behaviour to minimize user
impact.

Maybe you just want to have some deeper insight into a particular type of `if`
condition that is peppered throughout your codebase.

Regardless, this libary is here for your needs - whether you want a helpful log
entry, or you're just looking for an earth-shattering Kaboom.

## Contributions Welcome

If you have ideas for making Kaboom more widely useful, please add Issues or
PRs.

Have you found Kaboom to be useful in your projects? Let me know on Mastodon!

[@kboyd@phpc.social](https://phpc.social/@kboyd)

Thanks!