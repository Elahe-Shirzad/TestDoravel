<?php

namespace Modules\BaseModule\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Modules\BaseModule\Http\Requests\RegenerateUpdatedAtRequest;

class DateTimeController extends Controller
{
    /**
     * @param RegenerateUpdatedAtRequest $request
     * @return mixed
     */
    public function regenerateUpdatedAt(RegenerateUpdatedAtRequest $request)
    {
        $table = $request->table;
        $modelId = $request->model;
        $now = now();

        if (!Schema::hasTable($request->table)) {
            return Response::error(
                message: 'The specified table does not exist.'
            );
        }

        DB::table($table)
            ->where('id', $modelId)
            ->update(['updated_at' => $now]);

        return Response::success(
            data: verta($now)->format(jdateFormat('datetime_comma'))
        );
    }
}
