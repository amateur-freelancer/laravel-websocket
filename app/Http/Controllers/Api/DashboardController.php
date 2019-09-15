<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Dashboard;
use Illuminate\Http\Request;
use App\Helpers\Database;
use Validator;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dashboards = Dashboard::all();

        if ($request->profile_key) {
            $dashboards = $dashboards->where('profile_key', $request->profile_key);
        }

        $dashboards = $dashboards;

        return $dashboards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:255|unique:dashboards',
            'title' => 'string|max:255',
            'info' => 'string|max:255',
            'color' => 'string|max:20',
            'sort_order' => 'integer',
            'profile_key' => 'sometimes|string|max:255'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        DB::beginTransaction();
        $dashboard = new Dashboard([
            'code' => $request->code,
            'title' => $request->title,
            'info' => $request->info,
            'color' => $request->color,
            'sort_order' => $request->sort_order,
            'profile_key' => $request->profile_key
        ]);

        try {
            $dashboard->save();
            Database::createHistoryTable($dashboard);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return response()->json($dashboard, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        return $dashboard;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:255',
            'title' => 'string|max:255',
            'info' => 'string|max:255',
            'color' => 'string|max:20',
            'sort_order' => 'integer',
            'profile_key' => 'sometimes|string|max:255',
        ]);


        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $dashboard->code = $request->code;
        $dashboard->title = $request->title;
        $dashboard->info = $request->info;
        $dashboard->color = $request->color;
        $dashboard->sort_order = $request->sort_order;

        if ($request->profile_key) {
            $dashboard->profile_key = $request->profile_key;
        }

        $dashboard->save();

        return response()->json($dashboard, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $dashboard = Dashboard::where('code', $code)->first();

        try {
            DB::beginTransaction();
            $dashboard->delete();
            Database::dropHistoryTable($dashboard->code);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return response()->json(null, 204);
    }
}
