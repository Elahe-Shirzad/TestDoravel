<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\Controller;
//use App\Models\EducationalGradeMajor;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Bank\Generators\Banners\BankBanner;
use Modules\Bank\Generators\Sections\BankSection;
use Modules\Bank\Generators\Tables\BankTable;
use Modules\Bank\Generators\Tabs\BankTab;
use Modules\Bank\Http\Requests\StoreRequest;
use Modules\Bank\Http\Requests\UpdateRequest;
use Modules\Bank\Models\Bank;
use Modules\Bank\Models\BankLocation;
use Modules\Bank\Models\Location;
use Modules\Bank\Enums\Files\FileType;


//use Modules\EducationalGroup\Models\EducationalGroup;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        BladeLayout::table(BankTable::class);
        return view('bank::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $avatarFileTypeInfo = getFileType(FileType::BANK, 'bank_image');
        $avatarFileType = getUploadRequirements($avatarFileTypeInfo);
        $locations = prepareSelectComponentData(Location::all(), 'full_name');

        return view('bank::create', compact('locations', 'avatarFileType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {

        $data = array_merge($request->only([
            'name',
            'code',
            'color',
            'is_active',
            'type',
            'description',
            'published_at',
            'expired_at',
        ]), [
            'sort' => getNextSortValue(new Bank()),
            'image_id' => null
        ]);

        try {

            $bank = Bank::create($data);

            foreach ($request->input('location_id') as $locationItem => $value) {
                BankLocation::create([
                    "bank_id" => $bank->id,
                    "location_id" => $value
                ]);
            }

            uploadFile(
                module: 'Bank',
                field: 'image',
                dbField: 'image_id',
                fileTypeCode: 'bank_image',
                fileType: FileType::BANK,
                entity: $bank
            );

            return redirect()
                ->route('admin.base-information.banks.index')
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
    public function show(Bank $bank)
    {
        BladeLayout::data(compact('bank'));
        BladeLayout::tab(BankTab::class);
        BladeLayout::banner(BankBanner::class);
        BladeLayout::section(BankSection::class);

        return view('bank::show', compact('bank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        BladeLayout::data(compact('bank'));
        BladeLayout::section(BankSection::class);
        BladeLayout::banner(BankBanner::class);

        $locations = prepareSelectComponentData(Location::all(), 'full_name');

        $avatarFileTypeInfo = getFileType(FileType::BANK, 'bank_image');
        $avatarFileType = getUploadRequirements(
            documentType: $avatarFileTypeInfo,
            entity: Bank::class,
            entityId: $bank->id,
            entityFileRelation: 'image'
        );

        $locationsSelected = $bank->locations
            ->where('is_deleted', '=', '0')
            ->pluck('id')
            ->toArray();

        return view('bank::edit', compact('bank', 'locations', 'locationsSelected', 'avatarFileType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Bank $bank)
    {
        try {

            $data = collect($request->only([
                'name',
                'code',
                'color',
                'is_active',
                'type',
                'description',
                'published_at',
                'expired_at',
            ]))
                ->all();

            $bank->update($data);


            if ($request->has('location_id')) {
                $bank->locations()->sync($request->input('location_id'));
            }

            uploadFile(
                module: 'Bank',
                field: 'image',
                dbField: 'image_id',
                fileTypeCode: 'bank_image',
                fileType: FileType::BANK,
                entity: $bank
            );

            return redirect()
                ->route('admin.base-information.banks.index')
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
    public function destroy(Bank $bank)
    {

        try {
            $bank->delete();

            return redirect()
                ->route('admin.base-information.banks.index')
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
