<style>
.lists:not(:last-child) td {
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

<div ng-controller="Ctrl">

<div style="margin:0 0 0 0;display:inline-block;position: relative;background-color: #fff;border: 1px solid #aaa;border-right-width: 0;z-index: 2">
<table cellpadding="3" cellspacing="0" border="0" width="500" class="sch-profile" style="margin:0 0 0 10px">
    <tr>
        <th colspan="4" style="border-right:1px solid #aaa">
            <input ng-click="prev()" type="button" value="prev" />
            {{ page }}/{{ pages }}
            <input ng-click="next()" type="button" value="next" />
            <input ng-click="all()" type="button" value="顯示全部" />
            <input ng-click="reflash()" type="button" value="更新" />
        </th>
    </tr>
    <tr>        
        <th width="350">學校<input ng-model="searchSchoolText.sname" size="20" /></th>
        <th width="50" align="right">回收率</th>  
        <th width="50" align="right">學生數</th> 
        <th width="50" align="center" style="border-right:1px solid #aaa">名單</th>
    </tr>
    <tr> 
        <th>全國(已上傳學生資料)</th>    
        <th align="right">{{ total_rate }}</th>  
        <th align="right">{{ total }}</th>  
        <th style="border-right:1px solid #aaa"></th> 
    </tr>
    <tr ng-repeat="school in schools | filter:searchSchoolText | startFrom:(page-1)*20 | limitTo:limit" ng-style="selectedStyle(school,'tr')" class="lists">
        <td>{{ school.sname }}</td>    
        <td align="right">{{ school.rate }}</td>  
        <td align="right">{{ school.total }}</td>   
        <td align="center" ng-style="selectedStyle(school,'td')">
            <input type="button" value="開啟" ng-click="open(school.shid,school.sname)" ng-disabled="school.shid===school_selected" ng-hide="school.shid===null" />
        </td>  
    </tr>
   
</table>
</div>
    
<div style="margin:0 0 0 -1px;display:inline-block;position: absolute;background-color: #f5f5f5;border: 1px solid #aaa;z-index: 1">
<table cellpadding="3" cellspacing="0" border="0" width="350" class="sch-profile" style="margin:0 10px 10px 30px">
    <tr>
        <th colspan="2">
            <input ng-click="stu.prev()" type="button" value="prev" />
            {{ stu.page }}/{{ stu.pages }}
            <input ng-click="stu.next()" type="button" value="next" />
            <input ng-click="stu.all()" type="button" value="顯示全部" />
        </th>        
    </tr>
    <tr>        
        <th colspan="3">{{ school_name }}</th>
    </tr>
    <tr>        
        <th width="100">姓名
            <input ng-model="searchText.stdname" size="3" />
        </th>
        <th width="170">身分證
            <input ng-model="searchText.stdidnumber" />
        </th>
        <th width="80" align="center">刪除</th>
    </tr>
    <tr ng-repeat="student in students | filter:searchText | startFrom:(stu.page-1)*20 | limitTo:stu.limit" ng-style="deleteStyle(student)" class="lists">
        <td>{{ student.stdname }}</td>  
        <td>{{ student.stdidnumber }}****</td> 
        <td align="center">
            <input type="button" value="刪除" ng-click="student.confirm=1" ng-init="student.confirm=0" ng-hide="student.confirm" ng-disabled="student.deleted==='1'" />
            <input type="button" value="確認" ng-click="deleting=1;deleteStudent(student)" ng-init="deleting=0" ng-hide="!student.confirm" ng-disabled="deleting" style="color:#f00" />
        </td>
    </tr>
</table>
</div>

</div>

<script>
angular.module('app', [])
.filter('startFrom', function() {
    return function(input, start) {         
        return input.slice(start);
    };
}).controller('Ctrl', Ctrl);

function Ctrl($scope, $http, $filter) {
    $scope.page = 0;
    $scope.pages = 0;
    $scope.limit = 20;  
    $scope.schools = [];
            
    var getSchools = function() {
        $scope.schools = [];
        $http({method: 'POST', url: '<?=asset('ajax/'.$fileAcitver->intent_key.'/schools')?>', data:{} })
        .success(function(data, status, headers, config) {
            $scope.schools = data.schools;
            $scope.page = 1;
            $scope.max = $scope.schools.length;
            $scope.pages = Math.ceil($scope.max/$scope.limit);
            $scope.school_selected = '';
            $scope.total_rate = data.total_rate;
            $scope.total = data.total;
        })
        .error(function(e){
            console.log(e);
        });
    };
    $scope.reflash = getSchools;
    getSchools();
    
    $scope.stu = {page:0, pages:0, limit:20};
    $scope.students = [];
    
    $scope.next = function() {
        if( $scope.page < $scope.pages )
            $scope.page++;
    };
    
    $scope.prev = function() {
        if( $scope.page > 1 )
            $scope.page--;
    };
    
    $scope.all = function() {
        $scope.page = 1;
        $scope.limit = $scope.max;
        $scope.pages = 1;
    };
    
    $scope.selectedStyle = function(school, target) {
        if( target==='tr' ){
            return {                
                'background-color':  school.shid===$scope.school_selected ? '#f5f5f5' : ''
            };
        }
        if( target==='td' ){
            return {                
                'border-right': school.shid===$scope.school_selected ? '1px solid #f5f5f5' : '1px solid #aaa'
            };
        }
    };
    
    $scope.open = function(shid, name) {        
        $http({method: 'POST', url: '<?=asset('ajax/'.$fileAcitver->intent_key.'/list')?>', data:{shid:shid} })
        .success(function(data, status, headers, config) {
            $scope.school_name = name;
            $scope.school_selected = shid;
            $scope.students = data;            
            $scope.stu.page = 1;
            $scope.stu.max = $scope.students.length;
            $scope.stu.pages = Math.ceil($scope.stu.max/$scope.stu.limit);
        })
        .error(function(e){
            console.log(e);
        });
    };    
    
    $scope.$watchCollection('searchText', function(query) {
        $scope.stu.max = $filter("filter")($scope.students, query).length;
        $scope.stu.pages = Math.ceil($scope.stu.max/$scope.stu.limit);
        $scope.stu.page = 1;
    });  
    
    $scope.stu.next = function() {
        if( $scope.stu.page < $scope.stu.pages )
            $scope.stu.page++;
    };
    
    $scope.stu.prev = function() {
        if( $scope.stu.page > 1 )
            $scope.stu.page--;
    };
    
    $scope.stu.all = function() {
        $scope.stu.page = 1;
        $scope.stu.limit = $scope.stu.max;
        $scope.stu.pages = 1;
    };
    
    $scope.deleteStyle = function(student) {
        return {
            'text-decoration': student.deleted==='1'? 'line-through' : '',
            'background-color':  student.deleted==='1' ? '#eee' : ''
        };
    };
    
    $scope.deleteStudent = function(student) {        
        $http({method: 'POST', url: '<?=asset('ajax/'.$fileAcitver->intent_key.'/delete')?>', data:{cid:student.cid} })
        .success(function(data, status, headers, config) {
            if( data.saveStatus ){
                student.deleted = '1';
                student.confirm = 0;
            }
        })
        .error(function(e){
            console.log(e);
        });
    };  
}
</script>