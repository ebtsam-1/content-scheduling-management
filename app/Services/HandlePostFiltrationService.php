<?php 

namespace App\Services;

use Illuminate\Support\Facades\Auth;


class HandlePostFiltrationService
{
    public function handle($data)
    {
        $filters = ['user_id' => Auth::id()];
        if(isset($data['status'])) {
            $filters['status'] = $data['status'];
        }

        if(isset($data['start_date']) && isset($data['end_date'])) {
            
        }
    }
}

?>