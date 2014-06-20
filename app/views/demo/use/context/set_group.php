<style>
.sch-profile td {
    border-bottom: 1px solid #999;
}    
.sorter {
    color: #00f;
    cursor: pointer;
}
.sorter:hover {
    color: #00f;
    background-color: #fff;
}
</style>
學校: <input ng-model="searchText" />
<table cellpadding="3" cellspacing="0" border="0" width="1200" class="sch-profile" style="margin:10px 0 0 10px" ng-controller="Ctrl">
    <tr>
        <th><a class="sorter" herf="" ng-click="predicate = '-id'; reverse=false">編號</a>
            <a class="sorter" herf="" ng-click="predicate = 'id'; reverse=false">^</a>
        </th>		
        <th>
            <a class="sorter" herf="" ng-click="predicate = '-schools'; reverse=false">學校</a>
            <a class="sorter" herf="" ng-click="predicate = 'schools'; reverse=false">^</a>
        </th>
        <th>姓名</th>
        <th>開通</th>
		<th>email</th>
        <th>職稱</th>
        <th width="20" ng-repeat="group in groups">{{group.name}}</th>
    </tr>
    <tr ng-repeat="user in users | orderBy:predicate:reverse | filter:{schools:searchText}">
        <td>{{user.id | number}}</td>        
        <td><div ng-repeat="school in user.schools">{{school.id}} - {{school.sname}}</div></td>
        <td>{{user.name}}</td>
        <td>{{user.active}}</td>	
		<td>{{user.email}}</td>
        <td>{{user.title}}</td>
        <td ng-repeat="group in groups"><input type="checkbox" /></td>
    </tr>
    
<?
Config::set('demo.project', 'use');



$group = Cache::remember('sch_profile.group9999', 10, function() {
    return Group::with(array(
        'users.contact' => function($query){
            return $query->select('id', 'user_id', 'title', 'tel', 'fax');//,'schpeo','senior1','senior2','tutor','parent');
        },
        'users.schools'))->find(1);
});

$users = $group->users->map(function($user){   
    return array(
        'id'      => (int)$user->id,
        'active'  => $user->active,
		'email'   => $user->email,
        'schools' => $user->schools->map(function($school){
                         return array_only($school->toArray(), array('id', 'sname'));
                     })->all(),
        'name'    => $user->username,
        'title'   => array_get($user->contact, 'title'),
        'tel'     => array_get($user->contact, 'tel'),
        'fax'     => array_get($user->contact, 'fax'),
    );   
})->toJSON();

$groups = Group::all()->toArray();

$fileProvider = app\library\files\v0\FileProvider::make();
?>

    
</table>

<div ng-init="users = []"></div>

<script>
function Ctrl($scope) {
    $scope.users = angular.fromJson(<?=json_encode($users)?>);
    $scope.groups = angular.fromJson(<?=json_encode($groups)?>);
    $scope.predicate = 'id';
}
</script>