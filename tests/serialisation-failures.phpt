--TEST--
Ensure the serialisation points (ctor arguments when adding a new task to a
thread and putting some data into an ITC DS) handle failure correctly.
--FILE--
<?php

class Test implements Runnable
{
    public function run(){}
}

$thread = new thread();

try {
    $thread->addTask(Test::class, fopen(__FILE__, 'r'));
} catch (Error $e) {
    var_dump($e->getMessage());
}

$v = new Vector();

try {
    $v->push(fopen(__FILE__, 'r'));
} catch (Error $e) {
    var_dump($e->getMessage());
}

try {
    serialize($thread);
} catch (Exception $e) {
    var_dump($e->getMessage());
}

serialize($thread); // deliberate non-catch

--EXPECTF--
string(51) "Failed to serialise argument 1 of Thread::addTask()"
string(29) "Failed to serialise the value"
string(40) "Serialization of 'Thread' is not allowed"

Fatal error: Uncaught Exception: Serialization of 'Thread' is not allowed in %s:%d
Stack trace:
#0 %s(%d): serialize(Object(Thread))
#1 {main}
  thrown in %s on line %d