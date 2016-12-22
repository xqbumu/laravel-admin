<?php

namespace Encore\Incore;

use Closure;
use Encore\Incore\Layout\Content;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model as EloquentModel;

use InvalidArgumentException;

/**
 * abstract class Docore.
 */
abstract class DocoreAbstract
{
    
    /**
     * The module zone of the module to find the module config.
     *
     * @return string
     */
    const CONFIG_ZONE = '';

    /**
     * @var array
     */
    public static $script = [];

    /**
     * @var array
     */
    public static $css = [];

    /**
     * @var array
     */
    public static $js = [];

    /**
     * @var bool
     */
    protected static $initialized = false;

    /**
     * @var bool
     */
    protected static $bootstrapped = false;

    /**
     * Initialize.
     */
    public static function init()
    {
        if (!static::$initialized) {
            Form::registerBuiltinFields();
            Grid::registerColumnDisplayer();

            static::$initialized = true;
        }
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return Grid
     */
    public function grid($model, Closure $callable)
    {
        return new Grid($this->getModel($model), $callable);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return Form
     */
    public function form($model, Closure $callable)
    {
        static::init();
        static::bootstrap();

        return new Form($this->getModel($model), $callable);
    }

    /**
     * Build a tree.
     *
     * @param $model
     *
     * @return Tree
     */
    public function tree($model)
    {
        return new Tree($this->getModel($model));
    }

    /**
     * @param Closure $callable
     *
     * @return Content
     */
    public function content(Closure $callable)
    {
        static::init();
        static::bootstrap();

        Form::collectFieldAssets();

        return new Content($callable);
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public function getModel($model)
    {
        if ($model instanceof EloquentModel) {
            return $model;
        }

        if (is_string($model) && class_exists($model)) {
            return $this->getModel(new $model());
        }

        throw new InvalidArgumentException("$model is not a valid model");
    }

    /**
     * Add css or get all css.
     *
     * @param null $css
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function css($css = null)
    {
        if (!is_null($css)) {
            static::$css = array_merge(static::$css, (array) $css);

            return;
        }

        $css = array_get(Form::collectFieldAssets(), 'css', []);

        static::$css = array_merge(static::$css, $css);

        return view('incore::partials.css', ['css' => array_unique(static::$css)]);
    }

    /**
     * Add js or get all js.
     *
     * @param null $js
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function js($js = null)
    {
        if (!is_null($js)) {
            static::$js = array_merge(static::$js, (array) $js);

            return;
        }

        $js = array_get(Form::collectFieldAssets(), 'js', []);

        static::$js = array_merge(static::$js, $js);

        return view('incore::partials.js', ['js' => array_unique(static::$js)]);
    }

    /**
     * @param string $script
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function script($script = '')
    {
        if (!empty($script)) {
            static::$script = array_merge(static::$script, (array) $script);

            return;
        }

        return view('incore::partials.script', ['script' => array_unique(static::$script)]);
    }

    /**
     * Bootstrap.
     */
    public static function bootstrap()
    {
        if (!static::$bootstrapped) {
            if (file_exists($bootstrap = static::incore_path('bootstrap.php'))) {
                require $bootstrap;
            }
            static::$bootstrapped = true;
        }
    }

    /**
     * Get incore title.
     *
     * @return Config
     */
    public static function title()
    {
        return static::configs('title');
    }

    /**
     * Get namespace of controllers.
     *
     * @return string
     */
    public function controllerNamespace()
    {
        $directory = config('intendant.'.static::CONFIG_ZONE.'.directory');

        return 'Intendant\\'.ucfirst(basename($directory)).'\\Controllers';
    }

    /**
     * Admin url.
     *
     * @param $url
     *
     * @return string
     */
    public static function url($url)
    {
        $prefix = (string) config('intendant.'.static::CONFIG_ZONE.'.prefix');

        if (empty($prefix) || $prefix == '/') {
            return '/'.trim($url, '/');
        }

        return "/$prefix/".trim($url, '/');
    }

    /**
     * Admin url.
     *
     * @param $url
     *
     * @return string
     */
    public static function urlFull($path = null, $parameters = [], $secure = null)
    {
        $prefix = (string) config('intendant.'.static::CONFIG_ZONE.'.prefix');

        if (empty($prefix) || $prefix == '/') {
            return url('/'.trim($path, '/'), $parameters, $secure);
        }

        return url("/$prefix/".trim($path, '/'), $parameters, $secure);
    }

    /**
     * Left sider-bar menu.
     *
     * @return array
     */
    public function menu()
    {
        return call_user_func(array(\Docore::configs()['database']['menu_model'], 'toTree'));
    }

    /**
     * @return mixed
     */
    public static function user()
    {
        return Auth::guard(config('intendant.'.static::CONFIG_ZONE.'.intendant.zone'))->user();
    }

    /**
     * @return mixed
     */
    public static function configs($position = NULL)
    {
        if ($position) {
            return config('intendant.'.static::CONFIG_ZONE.'.'.$position);
        } else {
            return config('intendant.'.static::CONFIG_ZONE);
        }
    }

    /**
     * Get incore path.
     *
     * @param string $path
     *
     * @return string
     */
    public static function incore_path($path = '')
    {
        return ucfirst(\Docore::configs('directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get incore url.
     *
     * @param string $url
     *
     * @return string
     */
    public static function incore_url($url = '')
    {
        $prefix = trim(config('incore.prefix'), '/');

        return ($prefix ? "/$prefix" : '').'/'.trim($url, '/');
    }

    /**
     * @return string
     */
    public static function getModuleZone()
    {
        return config('intendant.'.static::CONFIG_ZONE.'.intendant.zone');
    }

    /**
     * @return string
     */
    public static function getModuleZoneUpper()
    {
        return ucfirst(static::getModuleZone());
    }

    /**
     * @return string
     */
    public static function getModelTableFullName($model)
    {
        $model = new $model;
        $table_prefix = $model->getConnection()->getTablePrefix();
        $table_name = $model->getTable();

        return $table_prefix.$table_name;
    }
}
