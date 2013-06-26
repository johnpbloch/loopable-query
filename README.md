# Loopable Query

Loopable Query lets you loop through `WP_Query` objects in a `foreach` loop.

```php
$featured_posts = new Loopable_Query( new WP_Query( array(
	'category_name' => 'featured',
	'posts_per_page' => 5,
) ) );

foreach( $featured_posts as $feature ) {
	echo '<p><a href="' . get_permalink() . '">';
	the_title();
	echo '</a></p>'
}
```

If you don't pass in a custom query object, it will use the global
`$wp_query` object, letting you use this for the main query object.

### Ok, so... why?

Why not? Sometimes you want a simpler way to handle looping through posts.

### Installing

Use [Composer](http://getcomposer.org) to add Loopable Query to your project.

```
{
	"require": {
		"johnpbloch/loopable-query": "~0.1"
	}
}
```

### Contributing

Pull requests are welcome. Loopable Query has unit tests. To run them, clone the repository, install composer, and run the following commands:

```
composer install --dev
vendor/bin/phpunit
```

### License

Loopable Query is licensed under the GPL version 2 or later.