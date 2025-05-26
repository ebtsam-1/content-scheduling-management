<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class HandleScheduledPostsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         try{
            $posts = Post::where('status' , 'scheduled')->where('scheduled_time', '<=' , now())->get();
            error_log($posts); 
            foreach($posts as $post) {
                    $post->status = 'published';
                    $post->published_time = now();
                    $post->save();
                }

                error_log('publishing posts');
        }catch(\Exception $e){
            error_log($e);  
        }
    }
}
