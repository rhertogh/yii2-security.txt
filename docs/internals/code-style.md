Yii2 security.txt Code Style
============================

Since this package is designed for Yii2 we're following the [Yii 2 Core Framework Code Style](
https://github.com/yiisoft/yii2/blob/master/docs/internals/core-code-style.md#yii-2-core-framework-code-style)
and [Yii 2 View Code Style](
https://github.com/yiisoft/yii2/blob/master/docs/internals/view-code-style.md#yii-2-view-code-style)

PHP CodeSniffer
---------------
To validate the source code you can use "PHP CodeSniffer", run:   
`composer phpcs`  
Often the found violations can be fixed automatically, to do so run:  
`composer phpcbf`  
> Warning: always validate code changes that were done automatically

For more information, please see https://github.com/squizlabs/PHP_CodeSniffer
