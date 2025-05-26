<?php

namespace App\Http\Controllers\Api;

use App\Models\Platform;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlatformResource;
use App\Repositories\PlatformRepository;
use App\Http\Requests\StorePlatformRequest;
use App\Http\Requests\UpdatePlatformRequest;

class PlatformController extends Controller
{
        public function __construct(private PlatformRepository $platformRepository)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('id', false);
        $paginate = $request->get('paginate', false);
        $status = $request->get('status', '*');
        $filters = ['status' => $status];

        $records = $this->platformRepository->get($search, $filters, false,$paginate);
        return response()->json(['records' => PlatformResource::collection($records)->resource]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlatformRequest $request)
    {
        $data = $request->validated();
        $record = $this->platformRepository->store($data);
        return response()->json(['message' => 'Successfully created',]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Platform $platform)
    {
        return response()->json(['record' => PlatformResource::make($platform)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlatformRequest $request, Platform $platform)
    {
        $data = $request->validated();
        $this->platformRepository->update($platform->id,$data);

        return response()->json(['message' => 'Successfully Updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Platform $platform)
    {
        $this->platformRepository->destroy($platform->id);
        return response()->json(['message' => 'Successfully deleted']);
    }
}
