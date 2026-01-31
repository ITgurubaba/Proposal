<?php

namespace App\Helpers\Admin;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\SlugManager;
use App\Models\ThemePage;
use Illuminate\Support\Arr;

class SlugHelper
{
    public static function geSlugPrefix($modelClass = BlogPost::class): string
    {
        return match ($modelClass) {
            BlogCategory::class => "blog/category",
            BlogPost::class => "blog",
            Service::class => "service",
            Product::class => "products",
            ProductCategory::class=>'products/category',
            default => "",
        };
    }
    public static function SlugAvailable($slug = "",$data = []):bool
    {
        if($slug && $slug!=="")
        {
            if (Arr::has($data,['model_id','model_class']))
            {
                $count = 0;
                switch ($data['model_class'])
                {
                    case BlogCategory::class:
                        $count += BlogCategory::whereHas('getSlug',function ($q) use ($slug){
                                   $q->where('slug',$slug);
                            })->where('id','!=',$data['model_id'])->count()>0;
                        break;
                    case Service::class:
                        $count += Service::whereHas('getSlug',function ($q) use ($slug){
                                $q->where('slug',$slug);
                            })->where('id','!=',$data['model_id'])->count()>0;
                        break;
                    case BlogPost::class:
                        $count += BlogPost::whereHas('getSlug',function ($q) use ($slug){
                                $q->where('slug',$slug);
                            })->where('id','!=',$data['model_id'])->count()>0;
                        break;
                    default:
                        $count += ThemePage::whereHas('getSlug',function ($q) use ($slug){
                                $q->where('slug',$slug);
                            })->where('id','!=',$data['model_id'])->count()>0;
                }
                return $count === 0;
            }
        }
        return false;
    }

    public static function createOrUpdate(string $slug = "",string $name = "",mixed $model_id ="" , $model_class = BlogPost::class): ?bool
    {
        try {
            $slugModel = SlugManager::where([
                'model'=>$model_class,
                'model_id'=>$model_id,
            ])->first();
            if ($slugModel)
            {
                $slugModel->name = $name;
                $slugModel->slug = $slug;
                $slugModel->save();
            }
            else
            {
                SlugManager::create([
                    'prefix'=>self::geSlugPrefix($model_class),
                    'model'=>$model_class,
                    'model_id'=>$model_id,
                    'name'=>$name,
                    'slug'=>$slug
                ]);
            }
            return true;
        }
        catch (\Exception $exception)
        {
            return false;
        }
    }

}
