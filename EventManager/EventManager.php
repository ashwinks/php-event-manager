<?php

class EventManager
{

    public static $instance = null;

    private $_listeners = array();

    /**
     *
     * @return EventManager
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $event_name
     * @param array $data
     * @return EventManager
     */
    public function emit($event_name, array $data = array())
    {
        $listener = $this->getListener($event_name);
        if (!$listener) {
            return $this;
        }

        foreach ($listener as $event) {
            call_user_func($event['callback'], $data);
        }

        return $this;
    }

    /**
     *
     * @param string $event_name
     * @param mixed $callback
     * @param int $priority
     * @return EventManager
     */
    public function on($event_name, $callback, $priority = 1)
    {
        return $this->registerEvent($event_name, $callback, $priority);
    }

    /**
     *
     * @param string $event_name
     * @return EventManager
     */
    public function detach($event_name)
    {
        return $this->deRegisterEvent($event_name);
    }

    /**
     *
     * @param string $event_name
     * @param mixed $callback
     * @param int $priority
     * @return EventManager
     */
    public final function registerEvent($event_name, $callback, $priority)
    {
        $event_name = trim($event_name);

        if (!isset($this->_listeners[$event_name])) {
            $this->_listeners[$event_name] = array();
        }

        $event = array(
            'event_name' => $event_name,
            'callback' => $callback,
            'priority' => (int)$priority
        );

        array_push($this->_listeners[$event_name], $event);

        if (count($this->_listeners[$event_name]) > 1) {
            usort($this->_listeners[$event_name], array($this, '_sortListenerByPriority'));
        }

        return $this;
    }

    /**
     *
     * @param string $event_name
     * @return EventManager
     */
    public final function deRegisterEvent($event_name)
    {
        if (isset($this->_listeners[$event_name])) {
            unset($this->_listeners[$event_name]);
        }

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getListeners()
    {
        return $this->_listeners;
    }

    /**
     * @param $listener
     * @return bool | array
     */
    public function getListener($listener)
    {
        if (isset($this->_listeners[$listener])) {
            return $this->_listeners[$listener];
        }

        return false;
    }

    private function _sortListenerByPriority($a, $b)
    {
        if ($a['priority'] == $b['priority']) {
            return 0;
        }

        return ($a['priority'] < $b['priority']) ? -1 : 1;
    }

}