<?php namespace Orchestra\Asset;

use RuntimeException;

class DependencyResolver
{
    /**
     * Sort and retrieve assets based on their dependencies.
     *
     * @param  array  $assets
     *
     * @return array
     */
    public function arrange($assets)
    {
        list($original, $sorted) = [$assets, []];

        $this->replaceAssetDependencies($assets);

        while (count($assets) > 0) {
            foreach ($assets as $asset => $value) {
                $this->evaluateAsset($asset, $value, $original, $sorted, $assets);
            }
        }

        return $sorted;
    }

    /**
     * Evaluate an asset and its dependencies.
     *
     * @param  string  $asset
     * @param  string  $value
     * @param  array   $original
     * @param  array   $sorted
     * @param  array   $assets
     *
     * @return void
     */
    protected function evaluateAsset($asset, $value, $original, &$sorted, &$assets)
    {
        // If the asset has no more dependencies, we can add it to the sorted
        // list and remove it from the array of assets. Otherwise, we will
        // not verify the asset's dependencies and determine if they've been
        // sorted.
        if (count($assets[$asset]['dependencies']) == 0) {
            $sorted[$asset] = $value;

            unset($assets[$asset]);
        } else {
            $this->evaluateAssetWithDependencies($asset, $original, $sorted, $assets);
        }
    }

    /**
     * Evaluate an asset with dependencies.
     *
     * @param  string  $asset
     * @param  array   $original
     * @param  array   $sorted
     * @param  array   $assets
     *
     * @return void
     */
    protected function evaluateAssetWithDependencies($asset, $original, &$sorted, &$assets)
    {
        foreach ($assets[$asset]['dependencies'] as $key => $dependency) {
            if (! $this->dependencyIsValid($asset, $dependency, $original, $assets)) {
                unset($assets[$asset]['dependencies'][$key]);

                continue;
            }

            // If the dependency has not yet been added to the sorted
            // list, we can not remove it from this asset's array of
            // dependencies. We'll try again onthe next trip through the
            // loop.
            if (isset($sorted[$dependency])) {
                unset($assets[$asset]['dependencies'][$key]);
            }
        }
    }

    /**
     * Verify that an asset's dependency is valid.
     *
     * A dependency is considered valid if it exists, is not a circular
     * reference, and is not a reference to the owning asset itself. If the
     * dependency doesn't exist, no error or warning will be given. For the
     * other cases, an exception is thrown.
     *
     * @param  string  $asset
     * @param  string  $dependency
     * @param  array   $original
     * @param  array   $assets
     *
     * @return bool
     *
     * @throws \RuntimeException
     */
    protected function dependencyIsValid($asset, $dependency, $original, $assets)
    {
        // Determine if asset and dependency is circular.
        $isCircular = function ($asset, $dependency, $assets) {
            return isset($assets[$dependency]) && in_array($asset, $assets[$dependency]['dependencies']);
        };

        if (! isset($original[$dependency])) {
            return false;
        } elseif ($dependency === $asset) {
            throw new RuntimeException("Asset [$asset] is dependent on itself.");
        } elseif ($isCircular($asset, $dependency, $assets)) {
            throw new RuntimeException("Assets [$asset] and [$dependency] have a circular dependency.");
        }

        return true;
    }

    /**
     * Replace asset dependencies.
     *
     * @param  array  $assets
     *
     * @return void
     */
    protected function replaceAssetDependencies(&$assets)
    {
        foreach ($assets as $asset => $value) {
            $replaces = $value['replaces'];

            if (empty($replaces)) {
                continue;
            }

            foreach ($replaces as $replace) {
                unset($assets[$replace]);
            }

            $this->resolveDependenciesForAsset($assets, $asset, $replaces);
        }
    }

    /**
     * Resolve asset dependencies after replacement.
     *
     * @param  array   $assets
     * @param  string  $asset
     * @param  array   $replaces
     *
     * @return array
     */
    protected function resolveDependenciesForAsset(&$assets, $asset, $replaces)
    {
        foreach ($assets as $name => $value) {
            $changed = false;

            foreach ($value['dependencies'] as $key => $dependency) {
                if (in_array($dependency, $replaces)) {
                    $changed = true;
                    unset($value['dependencies'][$key]);
                }
            }

            if ($changed) {
                $value['dependencies'][]       = $asset;
                $assets[$name]['dependencies'] = $value['dependencies'];
            }
        }

        $assets[$asset]['replaces'] = [];
    }
}
