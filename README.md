Fuel ModelHelper
================

Helper trait for [fuel/orm](https://github.com/fuel/orm/tree/1.7/master/classes/model.php) and [fuel/core](https://github.com/fuel/core/tree/1.7/master/classes/model/crud.php) models.

This trait can be used for rapid development of models and for a better separation of the properties and the column names. If `$_rename_properties` is set accordingly, an object is ready to get or set properties of the model with the methods `get...()` and `set...()`, which saves a lot of typing.

## Requirements

* FuelPHP 1.7+
* PHP 5.4+

It is recommended to use this trait only if you need to create many model in a short time, eg during a refactoring.

## Example

Create a model. You can extend the `\Fuel\Core\Model_Crud` or `\Orm\Model` class.

```php
<?php

namespace Model;

/**
 * Model Post
 *
 * @package     App
 */

class Post extends \Model_Crud
{

	use \Trait_ModelHelper;

	/**
	 * @var  string  $_table_name  The table name (must set this in your Model)
	 */
	protected static $_table_name = 'posts';

	/**
	 * @var  array  $_properties  The table column names (must set this in your Model to use)
	 */
	protected static $_properties = array(
		'id',
		'title',
		'content',
		'author',
		'created_at',
		'updated_at',
	);

	/**
	 * @var  string  $_rename_properties  The named properties (must set this in your Model)
	 */
	protected static $_rename_properties = array(
		'Id'        => 'id',
		'Title'     => 'title',
		'Content'   => 'content',
		'Author'    => 'author',
		'CreatedAt' => 'created_at',
		'UpdatedAt' => 'updated_at',
	);

}

```

Create or update an entry like this:

```php
// Create a post
$post = Model\Post::forge();

// Load a post
$post = Model\Post::forge(1);

$post->setTitle($title)
	->setContent($content)
	->setCreatedAt(time())
	->setAuthor($author)
	->save();
```

Or get the properties like this:

```php
$post = Model\Post::forge(1);

echo $post->getId();
// returns '1'

echo $post->getTitle();
// returns 'Unnamed post'

echo $post->getContent();
// returns 'Lorem ipsum dolor sit amet, duo ...'
```

You can aliasing different methods to the same property.

```php
	protected static $_rename_properties = array(
		'Author'    => 'author',
		'Username'  => 'author',
	);

$post = Model\Post::forge(1);

echo $post->getAuthor();
// returns 'John Doe'

echo $post->getUsername();
// returns also 'John Doe'
```

And you can change the behavior of the magic setter/getter methods by writing your own methods.

```php
	protected static $_rename_properties = array(
		'Id'    => 'post_id',
	);
	
	public function setId($id)
	{
		throw new \Exception('Setting the ID of a model is not allowed.');
	}


$post = Model\Post::forge();

echo $post->setId(123);
// throws an Exception
```
