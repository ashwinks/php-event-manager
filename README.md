php-event-manager
=================

PHP Event Manager

A simple event manager for event driven applications. 

Example Usage

```php
<?php

// registering events
$em = EventManager::getInstance();
$em->on('some-event', function(){ echo 'some-event triggered'; }, 1);
$em->on('some-event', function(){ echo 'another handler'; }, 2);

// emitting events
$em->emit('some-event', array('somedata'));

?>
```
