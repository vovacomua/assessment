<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() :JsonResponse
    {
        $campaigns = Campaign::query()
            ->where('status', 'active')
            ->get();

        return response()->json([
            'success'   => true,
            'message'   => 'Campaign listed successfully',
            'data'      => $campaigns,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request) :JsonResponse
    {
        $campaign = Campaign::create($request->validated());

        return response()->json([
            'success'    => true,
            'message'   => 'Campaign created successfully',
            'data'      => $campaign,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign) :JsonResponse
    {
        $result = $campaign->update($request->validated());

        return response()->json([
            'success' => $result,
            'message' => 'Campaign updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign) :JsonResponse
    {
        $result = $campaign->delete();

        return response()->json([
            'success' => $result,
            'message' => 'Campaign deleted successfully',
        ]);
    }
}
