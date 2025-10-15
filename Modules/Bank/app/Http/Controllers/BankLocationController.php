<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\Controller;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Bank\Enums\Files\FileType;
use Modules\Bank\Generators\Banners\BankBanner;
use Modules\Bank\Generators\Filters\BankFilter;
use Modules\Bank\Generators\Sections\BankSection;
use Modules\Bank\Generators\Tabs\BankTab;
use Modules\Bank\Http\Requests\StoreRequest;
use Modules\Bank\Models\Bank;
use Modules\Bank\Models\BankLocation;
use Exception;
use Modules\Bank\Models\Location;

class BankLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function location(Bank $bank)
    {
        BladeLayout::data(compact('bank'));
        BladeLayout::section(BankSection::class);
        BladeLayout::tab(BankTab::class);
        BladeLayout::banner(BankBanner::class);
        BladeLayout::filter(BankFilter::class);


        $tableDataSource = BankLocation::query()
            ->leftJoin("locations", "locations.id","=","bank_location.location_id")
            ->where('bank_id', $bank->id)
            ->where('bank_location.bank_id', $bank->id)
            ->where('bank_location.is_deleted', 0)
            ->where('locations.is_deleted', 0)
            ->select(
                'bank_location.*',
                "locations.branch",
                "locations.service",
                "locations.square",
                "locations.avatar_id"
            );


        $tableModifierClosure = function (array $row) use ($bank): array {
            $row['bank_location_avatar_url'] = getFile($row['avatar_id'])?->url;
            $row['delete_route'] = route(
                'admin.base-information.banks.locations.destroy',
                [
                    'bank' => encryptValue($bank->id),
                    'location' => encryptValue($row['id'])
                ]
            );

            return $row;
        };

        return view('bank::location',compact('tableModifierClosure', 'tableDataSource','bank'));

    }

    public function edit(Bank $bank)
    {
        BladeLayout::data(compact('bank'));
        BladeLayout::section(BankSection::class);
        BladeLayout::tab(BankTab::class);
        BladeLayout::banner(BankBanner::class);

        $locations = prepareSelectComponentData(Location::all(), 'full_address');

        $locationsSelected = BankLocation::query()
            ->where('bank_id', $bank->id)
            ->where('is_deleted', 0)
            ->pluck('location_id')
            ->toArray();

        return view('bank::add-location',compact('locationsSelected','locations','bank'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request ,Bank $bank)
    {
        try {

            $selectedIds = $request->input('location_id', []);

            BankLocation::where('bank_id', $bank->id)
                ->delete();

            foreach ($selectedIds as $locationItem => $value) {
                BankLocation::create(
                    [
                    'bank_id' => $bank->id,
                    'location_id' => $value
                    ]);
            }

            return redirect()
                ->route('admin.base-information.banks.location'
                    , [
                        'bank' => encryptValue($bank->id),
                    ]
                )
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
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank,BankLocation $location)
    {
        try {
            $location->delete();

            return backWithSuccess('حذف با موفقیت انجام گردید.');
        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError('حذف با خطا مواجه شد');
        }
    }
}
