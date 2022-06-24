# kw_table

[![Build Status](https://travis-ci.org/alex-kalanis/kw_table.svg?branch=master)](https://travis-ci.org/alex-kalanis/kw_table)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/kw_table/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/kw_table/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/kw_table/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_table)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/kw_table.svg?v1)](https://packagist.org/packages/alex-kalanis/kw_table)
[![License](https://poser.pugx.org/alex-kalanis/kw_table/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_table)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/kw_table/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/kw_table/?branch=master)

Table engine for managing entries from datasources.

## PHP Installation

```
{
    "require": {
        "alex-kalanis/kw_table": "1.0"
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

