# kw_table

Table engine for managing entries from datasources.

## PHP Installation

```
{
    "require": {
        "alex-kalanis/kw_table": "2.0"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


## PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Add some external packages with connection to the local or remote services.

3.) Connect the "kalanis\kw_table\core\Table" into your app. Extends it for setting your case.

4.) Extend your libraries by interfaces inside the package.

5.) Just call setting and render


## Basics

At first you want to use ```\kalanis\kw_table\kw\Helper```, because compiling the whole table's
dependencies is really mindblowing. Then you can start to experiment with changing classes.
When you have enough experiences, then you can make your own extensions of provided classes.
Especially filtering forms are really complicated - so try them first as normal, external
libraries for generating forms. Used mapper is also something difficult to grasp.

On the other side - it's possible with a few changes to render whole table into CLI or Json.
As it's shown in Helper. Cli version uses kw_clipr/PrettyTable, so the result is in Markdown.

