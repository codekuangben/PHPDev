<?php

namespace MyLibs\Base;

/**
 * @brief Loader.php — ThinkPHP5源码分析
 * @url https://blog.csdn.net/d780793370/article/details/80860221
 * @brief Autoloading Classes
 * @url https://www.php.net/manual/en/language.oop5.autoload.php
 */
class MLoader
{
    public static $PHPSearchPathList;
    
    public static function appendPHPSearchPath($searchPath)
    {
        if (null == MLoader::$PHPSearchPathList)
        {
            MLoader::$PHPSearchPathList = [];
        }
        
        array_push(MLoader::$PHPSearchPathList, $searchPath);
    }
    
    public static function registerLoader($autoLoader = null)
    {
        // 注册系统自动加载
        // 自动加载关键配置，对调用对象会自动按规范引用对象文件
        spl_autoload_register($autoLoader ?: 'think\\Loader::autoload', true, true);
    }
    
    public static function loadClassInSearchPath($className)
    {
        if (null != MLoader::$PHPSearchPathList)
        {
            foreach(MLoader::$PHPSearchPathList as $searchPath)
            {
                $fullPath = $searchPath . "/" . $className . '.php';
                
                if(file_exists($fullPath))
                {
                    require_once($fullPath);
                    return true;
                }
            }
        }
        
        return false;
    }
        
//     public static function __autoload($className)
//     {
//         if ($file = self::_findFile($className))
//         {
//             // 非 Win 环境不严格区分大小写
//             if (!IS_WIN || pathinfo($file, PATHINFO_FILENAME) == pathinfo(realpath($file), PATHINFO_FILENAME))
//             {
//                 _includeFile($file);
//                 return true;
//             }
//         }
//     }
    
//     private static function _findFile($class)
//     {
//         // 类库映射
//         if (!empty(self::$map[$class]))
//         {
//             return self::$map[$class];
//         }
        
//         // 查找 PSR-4
//         $logicalPathPsr4 = strtr($class, '\\', DS) . EXT;
//         $first           = $class[0]; // 获取prefixLengthsPsr4对应下标
        
//         if (isset(self::$prefixLengthsPsr4[$first]))
//         {
//             foreach (self::$prefixLengthsPsr4[$first] as $prefix => $length)
//             {
//                 if (0 === strpos($class, $prefix))
//                 { // 判断类的命名空间
//                     foreach (self::$prefixDirsPsr4[$prefix] as $dir)
//                     {
//                         // 判断类的文件路径
//                         // 通过$prefixLengthsPsr4的值截取类文件名
//                         if (is_file($file = $dir . DS . substr($logicalPathPsr4, $length))) {
//                             return $file;
//                         }
//                     }
//                 }
//             }
//         }
        
//         // 查找 PSR-4 fallback dirs
//         // $fallbackDirsPsr4 其他的命名空间位置
//         foreach (self::$fallbackDirsPsr4 as $dir)
//         {
//             if (is_file($file = $dir . DS . $logicalPathPsr4))
//             {
//                 return $file;
//             }
//         }
//     }
    
//     function _includeFile($file)
//     {
//         return include $file;
//     }
}

require_once (dirname(__FILE__) . "/../../MyLibs/Common/SystemEnv.php");

MLoader::appendPHPSearchPath(\MyLibs\Common\SystemEnv::$MY_PHP_ROOT_PATH);
MLoader::registerLoader(array("MyLibs\Base\MLoader", "loadClassInSearchPath"));

?>