<?php
namespace Robo;

trait TaskAccessor
{
    /**
     * Commands that use TaskAccessor must provide 'getContainer()'.
     */
    public abstract function getContainer();

    /**
     * Provides the collection builder with access to all of the
     * protected 'task' methods available on this object.
     */
    public function getBuiltClass($fn, $args)
    {
        if (preg_match('#^task[A-Z]#', $fn)) {
            return call_user_func_array([$this, $fn], $args);
        }
    }

    /**
     * Convenience function. Use:
     *
     * $this->task('Foo', $a, $b);
     *
     * instead of:
     *
     * $this->getContainer()->get('taskFoo', [$a, $b]);
     *
     * Note that most tasks will define another convenience
     * function, $this->taskFoo($a, $b), declared in a
     * 'loadTasks' trait in the task's namespace.  These
     * 'loadTasks' convenience functions typically will call
     * $this->task() to ensure that task objects are fetched
     * from the container, so that their dependencies may be
     * automatically resolved.
     */
    protected function task()
    {
        $args = func_get_args();
        $name = array_shift($args);

        $collectionBuilder = $this->collectionBuilder();
        return $collectionBuilder->build($name, $args);
    }

    /**
     * Get a collection
     *
     * @return \Robo\CollectionCollection
     */
    protected function collection()
    {
        return $this->getContainer()->get('collection');
    }

    /**
     * Get a builder
     *
     * @return \Robo\Collection\CollectionBuilder
     */
    protected function collectionBuilder()
    {
        return $this->getContainer()->get('collectionBuilder', [$this]);
    }
}