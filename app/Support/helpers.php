<?php

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

if(! function_exists('uuid1')) {
    function uuid1() {
        $func_args = func_get_args();
        return Uuid::uuid1(...$func_args)->toString();
    }
}

if(! function_exists('uuid3')) {
    function uuid3() {
        $func_args = func_get_args();
        return Uuid::uuid3(...$func_args)->toString();
    }
}

if(! function_exists('uuid4')) {
    function uuid4() {
        $func_args = func_get_args();
        return Uuid::uuid4(...$func_args)->toString();
    }
}

if(! function_exists('uuid5')) {
    function uuid5() {
        $func_args = func_get_args();
        return Uuid::uuid5(...$func_args)->toString();
    }
}

if(! function_exists('directory_file_count')) {
    /**
     * 计算目录中常规文件的数量
     *
     * @param string $dir
     * @return int
     */
    function directory_file_count($dir) {
        if(!is_dir($dir)) {
            return 0;
        }
        $dirIterator = new DirectoryIterator($dir);
        $count = 0;
        foreach($dirIterator as $fileInfo) {
            if(! $fileInfo->isDot() && ! $fileInfo->isDir()) {
                $count++;
            }
        }
        return $count;
    }
}

if(! function_exists('transaction_save_many')) {
    /**
     * 在事务中保存多个Eloguent ORM model
     * @param array Illuminate\Database\Eloquent\Model $models
     */
    function transaction_save_many($models)
    {
        DB::transaction(function () use ($models) {
            array_map(function ($model) {
                $model->save();
            }, $models);
        });
    }
}
