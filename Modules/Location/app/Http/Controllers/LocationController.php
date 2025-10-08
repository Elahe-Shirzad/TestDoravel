<?php

namespace Modules\Location\Http\Controllers;

use App\Http\Controllers\Controller;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Bank\Enums\Files\FileType;
use Modules\Bank\Models\Bank;
use Modules\Bank\Models\BankLocation;
use Modules\Bank\Models\Location;
use Modules\Location\Generators\Tables\LocationTable;
use Modules\Location\Http\Requests\LocationStoreRequest;
use Modules\Location\Http\Requests\LocationUpdateRequest;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        BladeLayout::table(LocationTable::class);
        return view('location::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $isActive = prepareSelectComponentData(
            source: IsActive::class,
            moduleName: 'location'
        );

        $avatarFileTypeInfo = getFileType(FileType::LOCATION, 'location_avatar');
        $avatarFileType = getUploadRequirements($avatarFileTypeInfo);

        return view('location::create',compact('isActive','avatarFileType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationStoreRequest $request) {


        $data = array_merge($request->only([
            'branch',
            'square',
            'street',
            'alley',
            'color',
            'is_active',
            'service',
            'full_address',
            'description',
            'published_at',
            'expired_at',
        ]), [
            'sort' => getNextSortValue(new Location()),
            'avatar' => null
        ]);

        try {

            $location = Location::create($data);

            uploadFile(
                module: 'Location',
                field: 'avatar',
                dbField: 'avatar_id',
                fileTypeCode: 'location_avatar',
                fileType: FileType::LOCATION,
                entity: $location
            );

            return redirect()
                ->route('admin.base-information.locations.index')
                ->withFlash(
                    type: 'success',
                    message: "درج با موفقیت انجام شد",
                );
        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError('خطایی رخ داده است');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('location::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {

        $avatarFileTypeInfo = getFileType(FileType::LOCATION, 'location_avatar');

        $avatarFileType = getUploadRequirements(
            documentType: $avatarFileTypeInfo,
            entity: Location::class,
            entityId: $location->id,
            entityFileRelation: 'avatar'
        );

        $isActive = prepareSelectComponentData(
            source: IsActive::class,
            moduleName: 'location'
        );


        return view('location::edit',compact('location','avatarFileType','isActive'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationUpdateRequest $request, Location $location) {
        try {

            $data = $request->only([
                'branch',
                'square',
                'street',
                'alley',
                'color',
                'is_active',
                'service',
                'full_address',
                'description',
                'published_at',
                'expired_at',
            ]);

            $location->update($data);


            uploadFile(
                module: 'location',
                field: 'avatar',
                dbField: 'avatar_id',
                fileTypeCode: 'location_avatar',
                fileType: FileType::LOCATION,
                entity: $location
            );

            return redirect()
                ->route('admin.base-information.locations.index')
                ->withFlash(
                    type: 'success',
                    message: "بروزرسانی با موفقیت انجام شد",
                );
        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError('بروزرسانی با خطا مواجه شد');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location) {

        try {
            $location->delete();

            return redirect()
                ->route('admin.base-information.locations.index')
                ->withFlash(
                    type: 'success',
                    message: "حذف با موفقیت انجام شد",
                );
        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError('حذف با خطا مواجه شد');
        }
    }

}
