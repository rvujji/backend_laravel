<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\Workshop;
use App\Models\WorkshopOffering;

use Illuminate\Http\Request;

use App\Http\Resources\WorkshopOfferingResource;

class PublicOfferingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Offerings for Workshop
    |--------------------------------------------------------------------------
    */

    public function workshopOfferings(string $slug)
    {
        $workshop = Workshop::where('slug', $slug)
            ->firstOrFail();

        $offerings = WorkshopOffering::with([
            'owner',
        ])
            ->where('workshop_id', $workshop->id)
            ->where('status', 'published')
            ->orderBy('start_date')
            ->paginate();

        return ApiResponse::success(
            'Workshop offerings fetched successfully.',
            WorkshopOfferingResource::collection($offerings),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Offering Detail
    |--------------------------------------------------------------------------
    */

    public function show(string $slug)
    {
        $offering = WorkshopOffering::with([
            'workshop',
            'owner',
            'sessions',
            'sessions.trainer',
            'sessions.assistantTrainer',
        ])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return ApiResponse::success(
            'Workshop offering fetched successfully.',
            new WorkshopOfferingResource($offering),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Public Offering Listing
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = WorkshopOffering::with([
            'workshop',
            'owner',
        ])
            ->where('status', 'published');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('workshop', function ($q2) use ($search) {

                        $q2->where('title', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Delivery Mode
        |--------------------------------------------------------------------------
        */

        if ($request->filled('delivery_mode')) {

            $query->where(
                'delivery_mode',
                $request->delivery_mode
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Upcoming Only
        |--------------------------------------------------------------------------
        */

        if ($request->boolean('upcoming')) {

            $query->whereDate(
                'start_date',
                '>=',
                now()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ordering
        |--------------------------------------------------------------------------
        */

        $query->orderBy('start_date');

        $offerings = $query->paginate(
            $request->integer('per_page', 15)
        );

        return ApiResponse::success(
            'Public offerings fetched successfully.',
            WorkshopOfferingResource::collection($offerings),
        );
    }
}
