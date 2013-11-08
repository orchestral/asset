<?php namespace Orchestra\Asset;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Html\HtmlBuilder;

class Dispatcher
{
    /**
     * Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files = null;

    /**
     * Html builder instance.
     *
     * @var \Illuminate\Html\HtmlBuilder
     */
    protected $html = null;

    /**
     * Dependency resolver instance.
     *
     * @var DependencyResolver
     */
    protected $resolver = null;

    /**
     * Public path location.
     *
     * @var string
     */
    protected $path = null;

    /**
     * Use asset versioning.
     *
     * @var boolean
     */
    public $useVersioning = false;

    /**
     * Create a new asset dispatcher instance.
     *
     * @param  string  $name
     */
    public function __construct(Filesystem $files, HtmlBuilder $html, DependencyResolver $resolver, $path)
    {
        $this->files = $files;
        $this->html = $html;
        $this->resolver = $resolver;
        $this->path = $path;
    }

    /**
     * Enable asset versioning.
     *
     * @return void
     */
    public function addVersioning()
    {
        $this->useVersioning = true;
    }

    /**
     * Disable asset versioning.
     *
     * @return void
     */
    public function removeVersioning()
    {
        $this->useVersioning = false;
    }

    /**
     * Dispatch assets by group.
     *
     * @param  string  $group
     * @param  array   $assets
     * @return string
     */
    public function run($group, array $assets = array())
    {
        $html = '';

        if (! isset($assets[$group]) or count($assets[$group]) == 0) {
            return $html;
        }

        foreach ($this->resolver->arrange($assets[$group]) as $data) {
            $html .= $this->asset($group, $data);
        }

        return $html;
    }

    /**
     * Get the HTML link to a registered asset.
     *
     * @param  string  $group
     * @param  array   $asset
     * @return string
     */
    public function asset($group, $asset)
    {
        if (! isset($asset)) {
            return '';
        }

        // If the source is not a complete URL, we will go ahead and prepend
        // the asset's path to the source provided with the asset. This will
        // ensure that we attach the correct path to the asset.
        if (filter_var($asset['source'], FILTER_VALIDATE_URL) === false) {
            // We can only append mtime to locally defined path since we need
            // to extract the file.
            $file = $this->path.'/'.ltrim($asset['source'], '/');

            if ($this->useVersioning) {
                $modified = $this->files->lastModified($file);

                ! empty($modified) and $asset['source'] = $asset['source']."?{$modified}";
            }
        }

        return call_user_func_array(array($this->html, $group), array(
            $asset['source'],
            $asset['attributes'],
        ));
    }
}
