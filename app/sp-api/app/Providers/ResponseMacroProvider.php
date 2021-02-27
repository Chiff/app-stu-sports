<?php
//
//namespace App\Providers;
//
//
//use App\Models\SerializableModel;
//use Illuminate\Support\ServiceProvider;
//use JsonMapper\JsonMapper;
//use Laravel\Lumen\Http\ResponseFactory;
//
//class ResponseMacroProvider extends ServiceProvider
//{
//    public function boot(JsonMapper $mapper, ResponseFactory $factory)
//    {
//        $factory->macro('mapModel', function (SerializableModel $value) use ($factory, $mapper) {
//            return response()->json($value);
//        });
//    }
//}
