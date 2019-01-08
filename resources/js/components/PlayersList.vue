<template>
    <div class="container p-0">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form id="playerForm" @submit="playersSearch">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" @click="playersSearch"><i class="fas fa-search"></i></button>
                        </div>
                        <input type="text" class="form-control" @keypress="enterSearch" placeholder="Find substring in player description" v-model="searchFilters.search">
                    </div>
                </form>
                <div class="card card-default">
                    <div class="card-header">
                        Found <i v-if="loading" class="fas fa-spinner fa-spin"></i><span v-else>{{count}}</span>
                        <button class="btn btn-link right" type="button" @click="playersSearch"><i class="fas fa-sync"></i></button>
                    </div>
                    <div class="card-body p-0">
                        <i v-if="loading" class="fas fa-spinner fa-spin fa-lg p-3"></i>
                        <div class="table-responsive" v-else-if="players.length > 0">
                            <table class="table table-striped">
                                <thead> 
                                    <tr> 
                                        <th>ID</th> 
                                        <th>Name</th> 
                                        <th>Level</th> 
                                        <th>Score</th> 
                                        <th>Suspected</th> 
                                    </tr> 
                                    <tr> 
                                        <th><div class="input-group"><span class="input-group-addon">=</span><input type="text" class="form-control" @keypress="enterSearch" placeholder="id" v-model="searchFilters.id"></div></th> 
                                        <th><div class="input-group"><span class="input-group-addon">=</span><input type="text" class="form-control" @keypress="enterSearch" placeholder="name" v-model="searchFilters.name"></div></th> 
                                        <th><div class="input-group"><span class="input-group-addon">=</span><input type="text" class="form-control" @keypress="enterSearch" placeholder="level" v-model="searchFilters.level"></div></th> 
                                        <th><div class="input-group"><span class="input-group-addon">=</span><input type="text" class="form-control" @keypress="enterSearch" placeholder="score" v-model="searchFilters.score"></div></th> 
                                        <th><button class="btn btn-outline-secondary" type="button" @click="playersSearch"><i class="fas fa-search"></i></button></th> 
                                    </tr> 
                                </thead>
                                <tbody>
                                    <tr v-for="player in players"> 
                                        <th scope="row">{{player.id}}</th> 
                                        <td class="text-uppercase">{{player.name}}</td> 
                                        <td>{{player.level}}</td> 
                                        <td>{{player.score}}</td>
                                        <td><i v-if="player.suspected" class="fas fa-exclamation-circle"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                        <div class="table-responsive" v-else>
                            <table class="table table-striped">
                                <thead> 
                                    <tr> 
                                        <th>ID</th> 
                                        <th>Name</th> 
                                        <th>Level</th> 
                                        <th>Score</th> 
                                        <th>Suspected</th> 
                                    </tr> 
                                    <tr> 
                                        <th><div class="input-group"><span class="input-group-addon">=</span><input type="text" class="form-control" @keypress="enterSearch" placeholder="id" v-model="searchFilters.id"></div></th> 
                                        <th><div class="input-group"><span class="input-group-addon">=</span><input type="text" class="form-control" @keypress="enterSearch" placeholder="name" v-model="searchFilters.name"></div></th> 
                                        <th><div class="input-group"><span class="input-group-addon">=</span><input type="text" class="form-control" @keypress="enterSearch" placeholder="level" v-model="searchFilters.level"></div></th> 
                                        <th><div class="input-group"><span class="input-group-addon">=</span><input type="text" class="form-control" @keypress="enterSearch" placeholder="score" v-model="searchFilters.score"></div></th> 
                                        <th><button class="btn btn-outline-secondary" type="button" @click="playersSearch"><i class="fas fa-search"></i></button></th> 
                                    </tr> 
                                </thead>
                                <tbody>
                                    <div class="p-3">Unfortunately, no results were found, try changing the search terms.</div>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="card-footer player-pagination table-responsive" v-if="pagination.length > 0 && !loading" v-html="pagination">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    function reloadPlayers($vm, $url){
        var $params = {};
        if (typeof $url === 'undefined') {
            $url = '/api/v1/players';
            $params = {
                start: 1,
                n: 5
            };
            $.each($vm.searchFilters, function(key, val){
                if (val !== '') {
                    $params[key] = val;
                }
            });
        } 
        axios({
            method: 'get',
            url: $url, 
            params: $params
        }).then((response) => {
            if (typeof response.data.error === 'undefined') {
                $vm.players = response.data.items;
                $vm.count = response.data['x-total'];
                $vm.pagination = response.data.pagination;
            } else {
                $vm.$alertify.error(response.data.error);
            }
            $vm.loading = false;
        })
    }
    export default {
        components: {
        },
        props: [
            'initData'
        ],
        data: function(){
            return {
                sendProductOrderLoading: false,
                token: '',
                loading: true,
                searchFilters: {
                    search: '',
                    id: '',
                    name: '',
                    level: '',
                    score: ''
                },
                count: 0,
                players: [],
                pagination: '',
            }
        },
        mounted() {
            var $vm = this;
            this.token = $('meta[name="csrf-token"]').attr('content');
            reloadPlayers(this);
            $(document).on('click', '.player-pagination a', function(event) {
                event.preventDefault();
                $vm.loading = true;
                reloadPlayers($vm, $(this).attr('href'));
            });
        },
        filters: {
        },
        methods: {
            playersSearch: function (event) {
                event.preventDefault();
                this.loading = true;
                reloadPlayers(this);
            },
            enterSearch: function (event) {
                if (event.keyCode == 13) {
                    this.loading = true;
                    reloadPlayers(this);
                }
            },
        }
    }
</script>
<style>
    .players .breadcrumb {
        background-color: transparent;
    }
    .player-pagination {
        background-color: #fff;
    }
    .player-pagination .pagination {
        margin: 0;
    }
</style>
