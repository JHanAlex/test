<template>
    <v-container grid-list-xl>
        <v-layout row wrap justify-center>
            <v-flex xs12>
                <form id="playerForm" @submit.prevent="sendData">
                    <v-card class="elevation-12">
                        <v-card-text>
                            <v-form>
                                <v-container grid-list-md>
                                    <v-layout row wrap>
                                        <v-flex sm12 md12>
                                            <v-text-field
                                                prepend-icon="fas fa-user"
                                                v-validate="'required|max:50'"
                                                v-model="form.name"
                                                counter="50"
                                                :class="{ 'is-invalid': form.errors.has('name') }"
                                                :error-messages="form.errors.has('name') ? form.errors.get('name') : errors.collect('name')"
                                                :label="$t('name')"
                                                data-vv-name="name"
                                                name="name"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex sm12 md12>
                                            <v-select
                                                prepend-icon="build"
                                                v-validate="'required'"
                                                v-model="form.level"
                                                :items="initData.levels"
                                                :class="{ 'is-invalid': form.errors.has('level') }"
                                                :error-messages="form.errors.has('level') ? form.errors.get('level') : errors.collect('level')"
                                                label="Level"
                                                data-vv-name="level"
                                                name="level"
                                            ></v-select>
                                        </v-flex>
                                        <v-flex sm12 md12>
                                            <v-text-field
                                                prepend-icon="fas fa-star"
                                                v-validate="'required|integer|min_value:0'"
                                                v-model="form.score"
                                                :class="{ 'is-invalid': form.errors.has('score') }"
                                                :error-messages="form.errors.has('score') ? form.errors.get('score') : errors.collect('score')"
                                                label="Score"
                                                data-vv-name="score"
                                                name="score"
                                                type="number"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex sm12 md12>
                                            <v-switch
                                                v-validate="'required'"
                                                v-model="form.suspected"
                                                :class="{ 'is-invalid': form.errors.has('suspected') }"
                                                :error-messages="form.errors.has('suspected') ? form.errors.get('suspected') : errors.collect('suspected')"
                                                label="Suspected"
                                                data-vv-name="suspected"
                                                name="suspected"
                                            ></v-switch>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </form>
            </v-flex>
            <v-flex xs12 class="text-center">
                <v-btn dark large color="blue" :loading="playerLoading" @click="sendData">
                    <v-icon left dark>fas fa-paper-plane</v-icon>{{$t('Store')}}
                </v-btn>
            </v-flex>
        </v-layout>
    </v-container>        
</template>

<script>
    import Form from 'vform';
    export default {
        components: {
            Form
        },
        props: [
            'initData'
        ],
        data: function(){
            return {
                form: new Form({
                    name: '',
                    level: '',
                    score: 0,
                    suspected: false,
                    token: ''
                }),
                playerLoading: false,
                token: ''
            }
        },
        mounted() {
            this.token = this.form.token = $('meta[name="csrf-token"]').attr('content');
        },
        filters: {
        },
        methods: {
            async sendData() {
                if (await this.formHasErrors()) {
                    this.$vuetify.goTo('#player_add', {offset: -100});
                    return;
                }
                this.playerLoading = true;
                this.form.post('/api/v1/player/store').then(({ data }) => { 
                    if (data.hasOwnProperty('success')) {
                        if (data.success) { 
                            this.$alertify.success(data.message);
                            this.form.reset();
                        }
                        else {
                           this.$alertify.error(data.error);
                        }
                    }   
                    this.playerLoading = false;
                }).catch((error) => {
                    this.$alertify.error('ERROR');
                    this.playerLoading = false;
                });
            }
        },
    }
</script>
