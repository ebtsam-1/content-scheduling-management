<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\PostsFiltrationRequest;
use App\Services\HandlePostFiltrationService;

class PostController extends Controller
{
    public function __construct(private PostRepository $postRepository, private HandlePostFiltrationService $handlePostFiltrationService)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index(PostsFiltrationRequest $request)
    {
        $data = $request->validated();
        $paginate = $data['paginate'] ?? false;
        $status = $data['status'] ?? '*';
        $filters = ['user_id' => Auth::id(), 'status' => $status];

        $dates = Arr::except($data, ['paginate', 'status']);

        $records = $this->postRepository->get(false, $filters, false,$paginate, 15, $dates);
        return response()->json(['records' => PostResource::collection($records)->resource]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        if($data['status'] == 'scheduled') {
            $this->authorize('check-if-user-reached-max-scheduled-posts-per-day');
        }

        if($data['status'] == 'published') {
            $data['published_time'] = now();
        }
        if(isset($data['image'])) {
            $image = $data['image'];
            $imageName = Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('post-images', $imageName, 'public'); 
            $data['image_url'] = 'post-images/' . $imageName;
        }

       
        $post = $this->postRepository->store(Arr::except($data, ['platforms', 'image']));
        $post->platforms()->sync($data['platforms']);
        return response()->json(['message' => 'Successfully created']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json(['record' => PostResource::make($post)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        
        $this->authorize('check-if-user-can-update-post', $post);
        $data = $request->validated();

        if(isset($data['image'])) {
            $image = $data['image'];
            $imageName = Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('post-images', $imageName, 'public'); 
            $data['image_url'] = 'storage/post-images/' . $imageName;
        }
        $this->postRepository->update($post->id,Arr::except($data, ['platforms', 'image']));

        if(isset($data['platforms'])) {
            $post->platforms()->detach();
            $post->platforms()->sync($data['platforms']);
        }


        return response()->json(data: ['message' => 'Successfully Updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->platforms()->detach();
        $this->postRepository->destroy($post->id);
        return response()->json(['message' => 'Successfully deleted']);
    }

     public function publish(Post $post)
    {
        
        $this->authorize('check-if-user-can-publish-post', $post);
        $this->postRepository->update($post->id, ['status' => 'published', 'published_time' => now()]);
        return response()->json(data: ['message' => 'Successfully published']);

    }

}

