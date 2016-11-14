angular.module('ngStruct', []);

angular.module('ngStruct', [])

.factory('structService', function($http, $timeout) {
    var selected = {schools: [], columns: {}};
    var status = {levels: [], page: 0, calculations: []};
    return {
        selected: selected,
        status: status
    }
})

.directive('structItems', function(structService) {
    return {
        restrict: 'E',
        replace: false,
        transclude: false,
        scope: {
            table: '=',
            column: '=',
            multiple: '='
        },
        template: `
            <md-input-container>
                <label>{{column.title}}</label>
                <md-select multiple ng-if="multiple && column.type!='slider'" style="margin:0"
                    placeholder="{{column.title}}"
                    ng-model="selected.columns[column.id].items"
                    md-on-open="loadItem(table, column)"
                    ng-change="toggleItems(column);column.selected = true">
                    <md-option ng-value="item" ng-repeat="item in column.items">{{item.name}}</md-option>
                </md-select>
                <md-select ng-if="!multiple && column.type!='slider'" style="margin:0"
                    placeholder="{{column.title}}"
                    ng-model="selected.columns[column.id].items"
                    md-on-open="loadItem(table, column)"
                    ng-change="toggleItems(column);column.selected = true">
                    <md-option ng-value="item" ng-repeat="item in column.items">{{item.name}}</md-option>
                </md-select>
            </md-input-container>
        `,
        link: function(scope, element) {
            scope.selected = structService.selected;
        },
        controller: function($scope, $http, $filter, $q) {

            $scope.loadItem = function(table, column) {
                if (column.items && column.itemsLoadBy == structService.selected.schools) {
                    return column.items;
                }

                deferred = $q.defer();
                $http({method: 'POST', url: 'getEachItems', data:{organizations: structService.selected.schools, table_id: table.id, rowTitle: column.title}})
                .success(function(data, status, headers, config) {
                    column.itemsLoadBy = structService.selected.schools;
                    table.disabled = data.items.length == 0;
                    column.items = data.items || [];
                    column.disabled = column.items.length == 0;
                    if (column.title == '年齡') column.disabled = true;
                    //$scope.population.columns[1].type = 'slider';
                    deferred.resolve(data.items);
                })
                .error(function(e) {
                    console.log(e);
                });

                return deferred.promise;
            };

            $scope.toggleItems = function(column) {
                //console.log(column)
                console.log(structService)
                if (structService.selected.columns[column.id]) {
                    if (!structService.selected.columns[column.id].rank) {
                        structService.selected.columns[column.id].rank = Object.keys(structService.selected.columns).length;
                    }
                    if (structService.selected.columns[column.id].items.length == 0) {
                    delete structService.selected.columns[column.id];
                    }
                    var selectedColumns = Object.keys(structService.selected.columns).map(function (key) { return structService.selected.columns[key]; });
                    structService.status.levels = $scope.getLevels($filter('orderBy')(selectedColumns, 'rank'));
                }
            };

            $scope.getLevels = function (columns) {
                var amount = 1;
                var levels = [];
                var rows = [];
                for (i in columns) {
                    var items = columns[i].items;//$filter('filter')(columns[i].items, {selected: true});
                    amount *= items.length;
                    levels[i] = {amount: amount, items: items};
                }
                //console.log(levels);
                for (var j = 0; j < amount; j++) {
                    rows[j] = [];
                    for (i in levels) {
                        var step = amount / levels[i].amount;
                        var part = parseInt(j / step);
                        var item = levels[i].items[part % levels[i].items.length];
                        if (part * step == j) {
                            item.rowspan = step;
                            rows[j].push(item);
                        }
                    }
                }

                return rows;
            }

        }
    };
})

.directive('planTable', function(structService) {
    return {
        restrict: 'A',
        replace: false,
        transclude: false,
        scope: {
            categories: '=',
            structClassShow: '=',
            tables: '=',
            toggleColumn: '=',
            toggleItems: '='
        },
        templateUrl: 'templatePlanTable',
        controller: function($scope, $http, $filter) {

            $scope.filterItems = {};

            $scope.structInClass = {
                '基本資料':{structs: [{title: '個人資料'}]},
                '就學資訊':{structs: [{title: '就學資訊'}]},
                '修課狀況':{structs: [{title: '完成教育專業課程'}, {title: '完成及認定專門課程'}]},
                '相關活動':{structs: [{title: '卓越師資培育獎學金'}, {title: '五育教材教法設計徵選活動獎'}, {title: '實踐史懷哲精神教育服務計畫'}, {title: '獲選為交換學生至國際友校'}, {title: '卓越儲備教師證明書'}]},
                '教育實習':{structs: [{title: '實際參與實習'}]},
                '教檢情形':{structs: [{title: '教師資格檢定'}]},
                '教師專長':{structs: [{title: '教師專長'}]},
                '教師甄試':{structs: [{title: '教甄資料'}]},
                '教師就業狀況':{structs: [{title: '在職教師'},{title: '公立學校代理代課教師'},{title: '儲備教師'},{title: '離退教師'}]},
                '語言檢定':{structs: [{title: '閩南語檢定'},{title: '客語檢定'}]}
            };

            $scope.addNewCalStruct = function() {
                console.log(structService)
                var calculation = {structs: [], results: {}};
                var structs = $filter('filter')($scope.tables, function(table) {
                    return structService.selected.columns && Object.keys(structService.selected.columns).length > 0;
                });
                console.log(structs);
                // for (var i in structs) {
                //     var columns = [];
                //     angular.forEach($filter('filter')(structs[i].columns, function(column, index, array) { return column.filter && column.filter!=''; }), function(column, key) {
                //         this.push({title: column.title, filter: column.filter.toString()});
                //     }, columns);
                //     calculation.structs.push({title: $scope.structs[i].title, columns: columns});
                // }
                calculation.structs = structs;

                structService.status.calculations.push(calculation);
                $scope.callCalculation();
            };

            $scope.callCalculation = function() {
                for (var i in structService.status.calculations) {
                    if ($.isEmptyObject(structService.status.calculations[i].results)) {
                        $scope.addCalculation(structService.status.calculations[i]);
                    }
                }
                //$scope.getTitle(); in integrate.php
                $scope.goToResultTable();
            };

            $scope.addCalculation = function(calculation) {
                //$http({method: 'POST', url: 'calculate', data:{structs: calculation.structs, columns: $scope.columns, schoolID: $scope.selected.schools}})
                $http({method: 'POST', url: 'calculate', data:{columns: structService.selected.columns, schoolID: structService.selected.schools}})
                .success(function(data, status, headers, config) {
                    console.log(data);
                    calculation.results = data.results;
                }).error(function(e) {
                    console.log(e);
                });
            };

            $scope.getRowSpan = function(structs) {
                var rowSpan = structs.length - $filter('filter')(structs, {expanded: true}).length;
                for (i in structs) {
                    if (structs[i].expanded) {
                        rowSpan += structs[i].columns.length;
                    };
                }
                return rowSpan;
            };

            $scope.showStruct = function(table, category) {
                var classTitle = category.title;
                category.expanded = true;
                $scope.structClassShow = true;
                for (var i in $scope.structInClass[classTitle].structs) {
                    for (var j in $scope.tables) {
                        if ($scope.tables[j].title == $scope.structInClass[classTitle].structs[i].title) {
                            $scope.tables[j].classExpanded = true;
                        }
                    }
                }
            };

            $scope.showFilter = function(struct) {
                $scope.structFilterShow = true;
                struct.expanded = !struct.expanded;
            };

            $scope.toggleStruct = function(struct) {
                if (struct.selected) {
                    angular.forEach($filter('filter')(struct.columns, {selected: true}), function(row) {

                        $scope.toggleColumn(row, struct);
                        row.selected = false;
                    });
                };
                struct.selected = !struct.selected;
            };

            $scope.goToResultTable = function() {
                structService.status.page = 1;
                //$location.hash('resultTable');
                //$anchorScroll();
            };

            $scope.destroyPopup = function() {
                $('#needHelp').popup('destroy');
                $('[name=needHelp2]').popup('destroy');
                $('[name=needHelp3]').popup('destroy');
                $('[name=needHelp4]').popup('destroy');
                $scope.helpChoosen = false;
            };

        }
    };
})

.directive('resultTable', function(structService) {
    return {
        restrict: 'A',
        replace: false,
        transclude: false,
        scope: {},
        templateUrl: 'templateResultTable',
        link: function(scope) {
            scope.status = structService.status;
        },
        controller: function($scope, $filter) {
        }
    };
})

.directive('structExplain', function() {
    return {
        restrict: 'E',
        replace: false,
        transclude: false,
        scope: {},
        templateUrl: 'templateExplain',
        controller: function($scope, $http, $filter) {
            $scope.explans = [];

            $http({method: 'POST', url: 'getExplans', data:{}})
            .success(function(data, status, headers, config) {
                console.log(data);
                $scope.tables = data.tables;
                $scope.categories = data.categories;
            }).error(function(e) {
                console.log(e);
            });

            $scope.getExplanSpan = function(table) {
                var explans = $scope.tables.slice(index, index+categories[table.title].size)
                var explanSpan = table.explans.length - $filter('filter')(table.explans, {expanded: true}).length;
                for (i in explans) {
                    if (explans[i].expanded) {
                        explanSpan += explans[i].explanations.length;
                    };
                }
                return explanSpan;
            };
        }
    };
});