<?php

namespace App\Helpers\Admin;

use App\Models\BlogCategory;
use App\Models\BlogPostAttribute;
use App\Models\BlogTag;
use App\Models\MetaData;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class BackendHelper
{
    const STATUS_OPTIONS = [
        ['value'=>1,'label'=>'Active'],
        ['value'=>0,'label'=>'Inactive'],
    ];

    const SLUG_PREFIX = [
        'blog'=>'blog/post/',
        'service'=>'service/',
        'product'=>'products/',
        'collection'=>'collection/',
        'productCategory'=>'products/category/',
    ];

    public static function getSlugPrefix($case = "blog"): string
    {
        $content = config('settings.app_base_url');
        $slugPrefix = self::SLUG_PREFIX[$case] ??'';
        return $content.$slugPrefix;
    }

    public static function getMetaData($model = null,$modelClass = BlogTag::class):array
    {
        if ($model && $model->metaData()->exists())
        {
            $data = $model->metaData->only([
                'model',
                'model_id',
                'title',
                'description',
                'os_image',
                'keywords',
                'author',
            ]);

            $data['keywords'] = checkData($data['keywords'])?explode(', ',$data['keywords']):[];

            return $data;
        }
        return [
            'model'=>$modelClass,
            'model_id'=>null,
            'title'=>null,
            'description'=>null,
            'os_image'=>null,
            'keywords'=>[],
            'author'=>null,
        ];
    }

    public static function getPostCategory($post = null):array
    {
        if ($post)
        {
            try {
                if ($post->categories()->exists())
                {
                    return $post->categories->pluck('id')->toArray();
                }
                return [];
            }
            catch (\Exception $exception)
            {
                return [];
            }
        }
        return [];
    }

    public static function getPostTags($post = null):array
    {
        if ($post)
        {
            try {
                if ($post->tags()->exists())
                {
                    return $post->tags->pluck('name')->toArray();
                }
                return [];
            }
            catch (\Exception $exception)
            {
                return [];
            }
        }
        return [];
    }

    public static function createOrUpdateMetaData($data = []):bool
    {

        if (gettype($data['keywords']) == "array")
        {
            $data['keywords'] = implode(', ',$data['keywords']);
        }

        try
        {
            $metaData = MetaData::where([
                'model'=>$data['model'],
                'model_id'=>$data['model_id'],
            ])->first();

            if($metaData)
            {
                $metaData->fill(Arr::only($data,[
                    'title',
                    'description',
                    'os_image',
                    'keywords',
                    'author',
                ]));
                $metaData->save();
            }
            else
            {
                MetaData::create($data);
            }
            return true;
        }
        catch (\Exception $exception)
        {
            return false;
        }
    }

    public static function createOrUpdatePostTags($data = "",$post_id = null):bool
    {
        try {
            $tagsArr = [];
            foreach ($data as $tagName)
            {
                if (checkData($tagName))
                {
                    $blogTag = BlogTag::findByName($tagName);
                    if(!$blogTag)
                    {
                        $blogTag = BlogTag::create([
                            'name'=>$tagName,
                            'status'=>1,
                        ]);
                    }
                    $count = BlogPostAttribute::where([
                        'post_id'=>$post_id,
                        'model'=>BlogTag::class,
                        'model_id'=>$blogTag->id,
                    ])->count();
                    if(!($count>0))
                    {
                        BlogPostAttribute::create([
                            'post_id'=>$post_id,
                            'model'=>BlogTag::class,
                            'model_id'=>$blogTag->id,
                        ]);
                    }
                    $tagsArr [] = $blogTag->id;
                }
            }
            BlogPostAttribute::where([
                'post_id'=>$post_id,
                'model'=>BlogTag::class,
            ])->whereNotIn('model_id',$tagsArr)->delete();
            return true;
        }
        catch (\Exception $exception)
        {
            return false;
        }
    }

    public static function createOrUpdatePostCategory(array $data = [],int $post_id = null):bool
    {
        try {
            $catArr = [];
            foreach ($data as $item)
            {
                $blogCategory = BlogCategory::find($item);
                if ($blogCategory)
                {
                    $count = BlogPostAttribute::where([
                        'post_id'=>$post_id,
                        'model'=>BlogCategory::class,
                        'model_id'=>$blogCategory->id,
                    ])->count();
                    if(!($count>0))
                    {
                        BlogPostAttribute::create([
                            'post_id'=>$post_id,
                            'model'=>BlogCategory::class,
                            'model_id'=>$blogCategory->id,
                        ]);
                    }
                    $catArr [] = $blogCategory->id;
                }
            }
            BlogPostAttribute::where([
                'post_id'=>$post_id,
                'model'=>BlogCategory::class,
            ])->whereNotIn('model_id',$catArr)->delete();
            return true;
        }
        catch (\Exception $exception)
        {
            return false;
        }
    }

    public static function getTime($format = "Y-m-d g:i A",$time = ""): string
    {
        try {
            return Carbon::parse($time)->format($format);
        }
        catch (\Exception $exception)
        {
            return "";
        }
    }

    public static function JsonDecode($data)
    {
        try
        {
            return isset($data) && $data!=""?json_decode($data,true):[];
        }
        catch (\Exception $exception) { return []; }
    }

    public static function JsonEncode($data): bool|string
    {
        try
        {
            return isset($data) && gettype($data) == "array"?json_encode($data):json_encode([]);
        }
        catch (\Exception $exception) { return json_encode([]); }
    }


}
