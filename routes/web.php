<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

function parseQBGroup($query, $group, $method = 'where')
{
    $query->{$method}(function ($subquery) use ($group) {
        $sub_method = $group['query']['logicalOperator'] === 'All' ? 'where' : 'orWhere';
        foreach ($group['query']['children'] as $child) {
            if ($child['type'] === 'query-builder-group') {
                parseQBGroup($subquery, $child, $sub_method);
            } else {
                parseQBRule($subquery, $child, $sub_method);
            }
        }
    });
}

function parseQBRule($query, $rule, $method)
{
    if ($rule['query']['rule'] === 'age') {
        $query->{$method}('age', $rule['query']['selectedOperator'], $rule['query']['value']);
    }

    if ($rule['query']['rule'] === 'job_title') {
        $query->{$method}('title', $rule['query']['selectedOperator'], $rule['query']['value']);
    }
}

Route::post('/results', function (\Illuminate\Http\Request $request) {
    $qb = $request->input('query');
    $group = [];
    $group['query'] = ['children' => $qb['children']];
    $group['query']['logicalOperator'] = $qb['logicalOperator'];
    $method = $qb['logicalOperator'] === 'All' ? 'where' : 'orWhere';

    $users = \App\User::query();

    parseQBGroup($users, $group, $method);

    return $users->get()->all();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
