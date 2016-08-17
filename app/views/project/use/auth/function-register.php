<?php

return [

    'init' => function($project) {

        $citys = DB::table('plat_public.dbo.lists')->where('type', 'city')->select('name', 'code')->get();

        return ['citys' => $citys, 'positions' => $project->positions];
    },

    'schools' => function() {

        $schools = DB::table('plat.dbo.organizations AS organizations')
            ->leftJoin('plat.dbo.organization_details AS details', 'organizations.id', '=', 'details.organization_id')
            ->where('details.citycode', Input::get('city_code'))
            ->whereIn('details.grade', [0, 1, 2, 'B', 'C', ''])
            ->select('organizations.id', 'details.name', 'details.sysname')
            ->orderBy('details.year', 'desc')
            ->get();

        return ['schools' => $schools];

    },

];