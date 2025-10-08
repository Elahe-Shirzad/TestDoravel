<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\Controller;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Bank\Generators\Banners\BankBanner;
use Modules\Bank\Generators\Sections\BankSection;
use Modules\Bank\Models\Bank;
use Modules\Bank\Models\BankLocation;
use Modules\Bank\Models\Location;

class BankLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function location(Bank $bank)
    {
        $tableDataSource = BankLocation::query()
            ->leftJoin("locations", "locations.id","=","bank_location.location_id")
            ->where('bank_id', $bank->id)
            ->select(
                'bank_location.*',
                "locations.branch",
                "locations.square",
                "locations.avatar_id"
            );

        $tableModifierClosure = function (array $row) use ($bank): array {
            $row['bank_location_avatar_url'] = getFile($row['avatar_id'])?->url;
//            $row['created_at'] = Carbon::parse($row['created_at'])->toJalali()->format('Y/m/d');
            return $row;
        };

        BladeLayout::data(compact('bank'));
        BladeLayout::section(BankSection::class);
        BladeLayout::banner(BankBanner::class);
        return view('bank::location',compact('tableModifierClosure', 'tableDataSource'));

    }
}
