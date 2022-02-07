<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HostGroup;
use App\Http\Requests\HostGroupRequest;

class HostGroupsController extends Controller
{
    public function index()
    {
        $hostGroups = HostGroup::all();
        return response()->json(compact('hostGroups'));
    }

    public function store(HostGroupRequest $request)
    {
        $hostGroup = HostGroup::create($request->validated());
        return response()->json(compact('hostGroup'));
    }

    public function show(int $id)
    {
        $hostGroup = HostGroup::find($id);
        return response()->json(compact('hostGroup'));
    }

    public function update(HostGroupRequest $request, int $id)
    {
        $success = HostGroup::find($id)->update($request->validated());
        return ($success) ? response('OK', 200) : response('Error', 500);
    }

    public function destroy(int $id)
    {
        $success = HostGroup::destroy($id);
        return ($success) ? response('OK', 200) : response('Error', 500);
    }

}
