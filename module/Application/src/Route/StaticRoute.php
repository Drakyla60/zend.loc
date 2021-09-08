<?php
namespace Application\Route;


// Пользовательский маршрут, обслуживающий "статические" веб-страницы.
use Laminas\Router\Http\RouteMatch;
use Laminas\Router\RouteInterface;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Stdlib\RequestInterface;
use Traversable;

class StaticRoute implements RouteInterface
{
    // Базовый каталог представления.
    protected $dirName;

    // Префикс пути для шаблонов представления.
    protected $templatePrefix;

    // Шаблон имени файла.
    protected $fileNamePattern = '/[a-zA-Z0-9_\-]+/';

    // Умолчания.
    protected $defaults;

    // Список собранных параметров.
    protected $assembledParams = [];

    // Конструктор.
    public function __construct($dirName, $templatePrefix,
                                $fileNamePattern, array $defaults = [])
    {
        $this->dirName = $dirName;
        $this->templatePrefix = $templatePrefix;
        $this->fileNamePattern = $fileNamePattern;
        $this->defaults = $defaults;
    }

    // Создаем новый маршрут с заданными опциями.
    public static function factory($options = [])
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(__METHOD__ .
                ' expects an array or Traversable set of options');
        }

        if (!isset($options['dir_name'])) {
            throw new Exception\InvalidArgumentException(
                'Missing "dir_name" in options array');
        }

        if (!isset($options['template_prefix'])) {
            throw new Exception\InvalidArgumentException(
                'Missing "template_prefix" in options array');
        }

        if (!isset($options['filename_pattern'])) {
            throw new Exception\InvalidArgumentException(
                'Missing "filename_pattern" in options array');
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = [];
        }

        return new static(
            $options['dir_name'],
            $options['template_prefix'],
            $options['filename_pattern'],
            $options['defaults']);
    }

    // Сопоставляем данный запрос.
    public function match(RequestInterface $request, $pathOffset = null)
    {
        // Гарантируем, что этот тип маршрута используется в HTTP-запросе
        if (!method_exists($request, 'getUri')) {
            return null;
        }

        // Получаем URL и его путь.
        $uri  = $request->getUri();
        $path = $uri->getPath();

        if($pathOffset!=null)
            $path = substr($path, $pathOffset);

        // Получаем массив сегментов пути.
        $segments = explode('/', $path);

        // Сверяем каждый сегмент с допустимым шаблоном имени файла
        foreach ($segments as $segment) {
            if(strlen($segment)==0)
                continue;
            if(!preg_match($this->fileNamePattern, $segment))
                return null;
        }

        // Проверяем, существует ли такой файл .phtml на диске
        $fileName = $this->dirName . '/'.
            $this->templatePrefix.$path.'.phtml';
        if(!is_file($fileName) || !is_readable($fileName)) {
            return null;
        }

        $matchedLength = strlen($path);

        // Подготавливаем объект RouteMatch.
        return new RouteMatch(array_merge(
            $this->defaults,
            ['page'=>$this->templatePrefix.$path]
        ),
            $matchedLength);
    }

    // Составляем URL с помощью параметров маршрута.
    public function assemble(array $params = [], array $options = [])
    {
        $mergedParams = array_merge($this->defaults, $params);
        $this->assembledParams = [];

        if(!isset($params['page'])) {
            throw new Exception\InvalidArgumentException(__METHOD__ .
                ' expects the "page" parameter');
        }

        $segments = explode('/', $params['page']);
        $url = '';
        foreach($segments as $segment) {
            if(strlen($segment)==0)
                continue;
            $url .= '/' . rawurlencode($segment);
        }

        $this->assembledParams[] = 'page';

        return $url;
    }

    // Получаем список параметров, использованных при составлении URL.
    public function getAssembledParams()
    {
        return $this->assembledParams;
    }
}