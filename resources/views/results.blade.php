@extends('layouts.app')
@section('css')
@endsection    
@section('content')
    <section id="players_add">
        <v-layout
          column
          wrap
          class="my-5"
          align-center
        >
            <v-flex xs12 sm4 class="my-3">
                <div class="text-xs-center">
                    <p class="display-2">Add Player</p>
                </div>
            </v-flex>
            <v-flex xs12>  
                <add-player :init-data="{{json_encode(['token' => csrf_token(), 'levels' => [Clear\Models\Player::ROOKIE, Clear\Models\Player::AMATEUR, Clear\Models\Player::PRO] ])}}"></add-player>  
            </v-flex>
        </v-layout>
    </section>
    <section id="player_list">
        <v-layout
          table
          wrap
          class="my-5 pt-5 pb-5"
          align-center
        >
            <v-flex xs12class="my-3">
                <div class="text-xs-center">
                    <p class="display-2 pt-3 pb-3 pl-5 pr-5">View Results</p>
                </div>
            </v-flex>
            <v-flex xs12>         
                <players-list :init-data="{{json_encode(['token' => csrf_token()])}}"></players-list>              
            </v-flex>
        </v-layout>                
    </section>
@endsection
