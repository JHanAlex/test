<?php

namespace Clear\Http\Controllers;

use Illuminate\Http\Request;
use Exception,
    Validator;
use Clear\Models\Player;
use Purifier;

class PlayerController extends Controller
{
    protected $allowedFilterKeys = array('id', 'name', 'score', 'level', 'search');
    protected $rulesFilters = array('id' => ['='], 'name' => ['LIKE', '='], 'score' => ['>', '>=', '=', '<', '<='], 'level' => ['='], 'search' => ['LIKE']);
    
    public function index()
    {
        $page_title = 'Tournament 101 - Final Results';
        return view('results', ['page_title' => $page_title, 'filters' => $this->allowedFilterKeys, 'filter_rules' => $this->rulesFilters]);
    }    
    
    public function get(Request $request)
    {
        try {
            $data = $request->all();
            $start = $data['start'];
            $n = $data['n'];
            $selectArray = ['id', 'name', 'level', 'score', 'suspected'];
            $query = Player::select($selectArray);
            $filters = [];
            foreach ($data as $key => $value) {
                if (in_array($key, $this->allowedFilterKeys)) {
                    switch ($key) {
                        case 'search': 
                            $filters[] = array(
                                'op' => 'whereRaw',
                                'data' => "(`players`.`id` LIKE ? OR `players`.`name` LIKE ? OR `players`.`level` LIKE ? OR `players`.`score` LIKE ?)",
                                'params' => ['%'.$value.'%', '%'.$value.'%', '%'.$value.'%', '%'.$value.'%']
                            );
                            break;
                        default:
                            $filters[] = array(
                                'field' => 'players.' . $key ,
                                'op' => isset($data[$key.'_op']) && in_array($data[$key.'_op'], $this->rulesFilters[$key]) ? $data[$key.'_op'] : '=',
                                'data' => $value
                            );
                            break;                        
                    }
                }
            }
            $this->queryFilters($filters, $query, $selectArray);
            $result = $query->paginate($n, ['*'], 'start');
            $pagination = $result->appends($data)->links();
            return response()->json(array('items' => $result->items(), 'x-total' => $result->total(), 'pagination' => $result->hasPages() ? $pagination->toHtml() : ''));           
        } catch (Exception $e) {
            return response()->json(array('error' => config('app.debug') ? $e->getMessage() : 'Data GET error'));
        }
    }
    
    public function store(Request $request)
    {
        $data = $request->all();
        $data['name'] = Purifier::clean($data['name'], 'remove_all');
        
        Validator::make($data, Player::$rules)->validate();
        
        try {
            $player = new Player();
            $player->fill($data);
            $player->save();
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }   
        return response()->json(['success' => true, 'message' => 'Player data saved successfully']);
    }
}
