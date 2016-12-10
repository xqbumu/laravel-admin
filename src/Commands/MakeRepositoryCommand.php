<?php

namespace Encore\Admin\Commands;

use Config;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:repository {repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a repository and interface';

    /**
     * @var
     */
    protected $repository;
    /**
     * @var
     */
    protected $target_dir;

    /**
     * @var
     */
    protected $stubs_dir;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     * @param Composer $composer
     */
    public function __construct(Filesystem $filesystem, Composer $composer)
    {
        parent::__construct();

        $this->files    = $filesystem;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //获取repository和model两个参数值
        $this->repository = $this->argument('repository');

        //自动生成RepositoryInterface和Repository文件
        $this->writeRepositoryAndInterface();

        //重新生成autoload.php文件
        $this->composer->dumpAutoloads();
    }

    private function writeRepositoryAndInterface()
    {
        if($this->createRepository($this->repository)){
            //若生成成功,则输出信息
            $this->info('Success to make a '.ucfirst($this->repository).' Repository and a '.ucfirst($this->repository).'Interface Interface');
        }
    }

    private function createRepository()
    {
        // 生成目标目录
        $this->target_dir = $this->getDirectory();
        // 创建文件存放路径, RepositoryInterface放在app/Repositories,Repository个人一般放在app/Repositories/Eloquent里
        $this->createDirectory($this->target_dir);
        // 生成两个文件
        return $this->createClass();
    }

    private function createDirectory($dir)
    {
        //检查路径是否存在,不存在创建一个,并赋予775权限
        if(! $this->files->isDirectory($dir)) {
            return $this->files->makeDirectory($dir, 0755, true);
        }
    }

    private function getDirectory()
    {
        return base_path('core'.DIRECTORY_SEPARATOR.'Intendant'.DIRECTORY_SEPARATOR.ucfirst($this->repository));
    }

    private function createClass()
    {
        //渲染模板文件,替换模板文件中变量值
        $templates = $this->templateStub();
        $res       = [];
        // 创建目录
        array_map([$this, 'createDirectory'], array_unique(array_map('dirname', array_keys($templates))));
        foreach ($templates as $path => $template) {
            //根据不同路径,渲染对应的模板文件
            $res[$path] = $this->files->put($path, $template);
        }
        return $res;
    }

    private function getInterfaceDirectory()
    {
        return Config::get('repository.directory_path');
    }

    private function getRepositoryName()
    {
        // 根据输入的repository变量参数,是否需要加上'Repository'
        $repositoryName = $this->getRepository();
        if((strlen($repositoryName) < strlen('Repository')) || strrpos($repositoryName, 'Repository', -11)){
            $repositoryName .= 'Repository';
        }
        return $repositoryName;
    }

    private function getInterfaceName()
    {
        return $this->getRepositoryName().'Interface';
    }

    private function templateStub()
    {
        // 获取两个模板文件
        $stubs        = $this->getStub();
        // 获取需要替换的模板文件中变量
        $templateData = $this->getTemplateData();
        $renderStubs  = [];
        foreach ($stubs as $key => $stub) {
            // 进行模板渲染
            $renderStubs[$key] = $this->getRenderStub($templateData, $stub);
        }
        return $renderStubs;
    }

    private function getStub()
    {
        $this->stubs_dir = __dir__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'intendant';
        return $this->list_file($this->stubs_dir);
    }

    private function list_file($dir) {
        $stub_list = [];
        $list = scandir($dir); // 得到该文件下的所有文件和文件夹
        foreach($list as $file) {//遍历
            $file_location=$dir."/".$file;//生成路径
            if(is_dir($file_location) && $file!="." &&$file!=".."){ //判断是不是文件夹
                $stub_list = array_merge($stub_list,$this->list_file($file_location)); //继续遍历
            } elseif($file!="." &&$file!="..") {
                $file_path = str_replace($this->stubs_dir, $this->target_dir, $file_location);
                $stub_list[$file_path] = $this->files->get($file_location);
            }
        }
        return $stub_list;
    }

    private function getTemplateData()
    {
        $stub_intendant_zone          = $this->repository;
        $stub_intendant_zone_upper    = ucfirst($this->repository);

        $templateVar = [
            'stub_intendant_zone'            => $stub_intendant_zone,
            'stub_intendant_zone_upper'      => $stub_intendant_zone_upper,
        ];

        return $templateVar;
    }

    private function getRenderStub($templateData, $stub)
    {
        foreach ($templateData as $search => $replace) {
            $stub = str_replace('{$'.$search.'}', $replace, $stub);
        }

        return $stub;
    }

    private function getModelName()
    {
        $modelName = $this->getModel();
        if(isset($modelName) && !empty($modelName)){
            $modelName = ucfirst($modelName);
        }else{
            // 若option选项没写,则根据repository来生成Model Name
            $modelName = $this->getModelFromRepository();
        }

        return $modelName;
    }

    private function getModelFromRepository()
    {
        $repository = strtolower($this->getRepository());
        $repository = str_replace('repository', '', $repository);
        return ucfirst($repository);
    }

}