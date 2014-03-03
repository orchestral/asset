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
    protected $files;

    /**
     * Html builder instance.
     *
     * @var \Illuminate\Html\HtmlBuilder
     */
    protected $html;

    /**
     * Dependency resolver instance.
     *
     * @var DependencyResolver
     */
    protected $resolver;

    /**
     * Public path location.
     *
     * @var string
     */
    protected $path;

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
     * @param  string      $group
     * @param  array       $assets
     * @param  string|null $prefix
     * @return string
     */
    public function run($group, array $assets = array(), $prefix = null)
    {
        $html = '';

        if (! isset($assets[$group]) || count($assets[$group]) == 0) {
            return $html;
        }

        is_null($prefix) || $this->path = rtrim($prefix, '/');

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

        $file = $this->path.'/'.ltrim($asset['source'], '/');

        // If the source is not a complete URL, we will go ahead and prepend
        // the asset's path to the source provided with the asset. This will
        // ensure that we attach the correct path to the asset.
        if (! $this->isLocalPath($file)) {
            $asset['source'] = $file;
        } elseif ($this->isLocalPath($asset['source'])) {
            // We can only append mtime to locally defined path since we need
            // to extract the file.
            if ($this->useVersioning) {
                $modified = $this->files->lastModified($file);

                ! empty($modified) && $asset['source'] = $asset['source']."?{$modified}";
            }
        }

        return call_user_func_array(array($this->html, $group), array(
            $asset['source'],
            $asset['attributes'],
        ));
    }

    /**
     * Determine if path is local.
     *
     * @param  string  $path
     * @return boolean
     */
    protected function isLocalPath($path)
    {
        if (starts_with($path, array('https://', 'http://', '//'))) {
            return false;
        }

        return (filter_var($path, FILTER_VALIDATE_URL) === false);
    }
}
