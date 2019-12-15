<?php
/**
 * @author Adam Engebretson
 * @license MIT
 *
 * @see https://github.com/laravie/html/blob/6.x/src/HtmlBuilder.php
 */

namespace Orchestra\Asset;

use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class HtmlBuilder
{
    /**
     * Create a new HTML builder instance.
     *
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     */
    public function __construct(UrlGeneratorContract $url)
    {
        $this->url = $url;
    }

    /**
     * Convert an HTML string to entities.
     *
     * @param  string  $value
     * @param  bool  $encoding
     *
     * @return string
     */
    public function entities($value, bool $encoding = false): string
    {
        if ($value instanceof Htmlable) {
            return $value->toHtml();
        }

        return \htmlentities($value, ENT_QUOTES, 'UTF-8', $encoding);
    }

    /**
     * Generate a link to a JavaScript file.
     *
     * @param  string  $url
     * @param  array  $attributes
     * @param  bool|null  $secure
     *
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function script(string $url, array $attributes = [], ?bool $secure = null): Htmlable
    {
        $attributes['src'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<script'.$this->attributes($attributes).'></script>');
    }

    /**
     * Generate a link to a CSS file.
     *
     * @param  string  $url
     * @param  array  $attributes
     * @param  bool|null  $secure
     *
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function style(string $url, array $attributes = [], ?bool $secure = null): Htmlable
    {
        $defaults = ['media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet'];
        $attributes = \array_merge($defaults, $attributes);
        $attributes['href'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<link'.$this->attributes($attributes).'>');
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     *
     * @return string
     */
    public function attributes(array $attributes): string
    {
        $html = [];
        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        foreach ((array) $attributes as $key => $value) {
            if (\is_array($value) && $key !== 'class') {
                foreach ((array) $value as $name => $val) {
                    $element = $this->attributeElement($key.'-'.$name, $val);
                    if (! \is_null($element)) {
                        $html[] = $element;
                    }
                }
            } else {
                $element = $this->attributeElement($key, $value);
                if (! \is_null($element)) {
                    $html[] = $element;
                }
            }
        }

        return \count($html) > 0 ? ' '.\implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param  string|int  $key
     * @param  mixed  $value
     *
     * @return mixed
     */
    protected function attributeElement($key, $value)
    {
        // For numeric keys we will assume that the value is a boolean attribute
        // where the presence of the attribute represents a true value and the
        // absence represents a false value.
        if (\is_numeric($key)) {
            return $value;
        }
        // Treat boolean attributes as HTML properties
        if (\is_bool($value) && $key !== 'value') {
            return $value ? $key : '';
        }
        if (\is_array($value) && $key === 'class') {
            return 'class="'.\implode(' ', $value).'"';
        }
        if (! \is_null($value)) {
            return $key.'="'.$this->entities($value).'"';
        }

        return null;
    }

    /**
     * Transform the string to an Html serializable object.
     *
     * @param string $html
     *
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    protected function toHtmlString(string $html): Htmlable
    {
        return new HtmlString($html);
    }
}
