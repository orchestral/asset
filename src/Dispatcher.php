<?php

namespace Orchestra\Asset;

use Illuminate\Support\Str;
use Collective\Html\HtmlBuilder;
use Illuminate\Filesystem\Filesystem;

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
     * @var \Collective\Html\HtmlBuilder
     */
    protected $html;

    /**
     * Dependency resolver instance.
     *
     * @var \Orchestra\Asset\DependencyResolver
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
     * @var bool
     */
    public $useVersioning = false;

    /**
     * Create a new asset dispatcher instance.
     *
     * @param \Illuminate\Filesystem\Filesystem  $files
     * @param \Collective\Html\HtmlBuilder  $html
     * @param \Orchestra\Asset\DependencyResolver  $resolver
     * @param string  $path
     */
    public function __construct(
        Filesystem $files,
        HtmlBuilder $html,
        DependencyResolver $resolver,
        string $path
    ) {
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
    public function addVersioning(): void
    {
        $this->useVersioning = true;
    }

    /**
     * Disable asset versioning.
     *
     * @return void
     */
    public function removeVersioning(): void
    {
        $this->useVersioning = false;
    }

    /**
     * Dispatch assets by group.
     *
     * @param  string  $group
     * @param  array  $assets
     * @param  string|null  $prefix
     *
     * @return string
     */
    public function run(string $group, array $assets = [], ?string $prefix = null): string
    {
        $html = '';

        if (! isset($assets[$group]) || \count($assets[$group]) == 0) {
            return $html;
        }

        \is_null($prefix) || $this->path = \rtrim($prefix, '/');

        foreach ($this->resolver->arrange($assets[$group]) as $data) {
            $html .= $this->asset($group, $data);
        }

        return $html;
    }

    /**
     * Get the HTML link to a registered asset.
     *
     * @param  string  $group
     * @param  array|null  $asset
     *
     * @return string
     */
    public function asset(string $group, ?array $asset = null): string
    {
        if (! isset($asset)) {
            return '';
        }

        $asset['source'] = $this->getAssetSourceUrl($asset['source']);

        $html = $this->html->{$group}($asset['source'], $asset['attributes']);

        return $html->toHtml();
    }

    /**
     * Determine if path is local.
     *
     * @param  string  $path
     *
     * @return bool
     */
    protected function isLocalPath(string $path): bool
    {
        if (Str::startsWith($path, ['https://', 'http://', '//'])) {
            return false;
        }

        return \filter_var($path, FILTER_VALIDATE_URL) === false;
    }

    /**
     * Get asset source URL.
     *
     * @param  string  $source
     *
     * @return string
     */
    protected function getAssetSourceUrl(string $source): string
    {
        // If the source is not a complete URL, we will go ahead and prepend
        // the asset's path to the source provided with the asset. This will
        // ensure that we attach the correct path to the asset.
        if (! $this->isLocalPath($file = $this->path.'/'.\ltrim($source, '/'))) {
            return $file;
        }

        return $this->getAssetSourceUrlWithModifiedTime($source, $file);
    }

    /**
     * Get asset source URL with Modified time.
     *
     * @param  string  $source
     * @param  string  $file
     *
     * @return string
     */
    protected function getAssetSourceUrlWithModifiedTime(string $source, string $file): string
    {
        if ($this->isLocalPath($source) && $this->useVersioning) {
            // We can only append mtime to locally defined path since we need
            // to extract the file.

            if (! empty($modified = $this->files->lastModified($file))) {
                return "{$source}?{$modified}";
            }
        }

        return $source;
    }
}
