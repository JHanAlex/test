<?php

namespace Clear\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    //'rookie', 'amateur', 'pro'
    const ROOKIE = 'rookie';
    const AMATEUR = 'amateur';
    const PRO = 'pro';
    
    protected $fillable = array(
        'name', 'level', 'score', 'suspected'
    );  
    
    protected $dates = ['created_at', 'updated_at'];
    
    public static $rules = [
        'name' => 'required|max:255',
        'level' => 'in:'.self::ROOKIE.','.self::AMATEUR.','.self::PRO,
        'score' => 'required|integer|min:0',
        'suspected' => 'required|boolean'
    ];
}
