<template>
    <v-scale-transition>
        <v-btn
            v-scroll="onScroll"
            v-show="fab"
            fab
            fixed
            bottom
            right
            :color="color"
            @click="toTop"
        >
        <v-icon :class="textColor">{{icon}}</v-icon>
        </v-btn>
    </v-scale-transition>
</template>

<script>
    export default {
        props: {
            color: {
                type: String,
                required: true
            },
            textColor: {
                type: String,
                required: true
            },
            icon: {
                type: String,
                required: true
            },
        },
        data: () => ({
            fab: false
        }),
        mounted() {
        },
        methods: {
            onScroll (e) {
                if (typeof window === 'undefined') return;
                const top = window.pageYOffset ||   e.target.scrollTop || 0;
                this.fab = top > 20;
            },
            toTop () {
                this.$vuetify.goTo(0);
            }            
        },
    }
</script>
