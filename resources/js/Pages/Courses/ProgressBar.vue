<template>
    <div>
        <div class="container relative w-100 h-8 rounded shadow mt-6 overflow-hidden">
            <div id="percentage">{{percentage}}%</div>
            <div class="progression" :style="'width:' + percentage + '%'">

            </div>
        </div>
    </div>
</template>

<script>
//import {eventBus} from '../../eventBus'
export default {
    props: ['watchedEp', 'courseEp'],

    data(){
        return{
            watched : this.watchedEp,
        }
    },

    computed: {
        percentage(){
            return Math.ceil(this.watched.length / this.courseEp.length * 100)
        }
    },

    mounted(){
       eventBus.on('toggleProgress', data => this.watched = data)
    }
}
</script>

<style>
.progression{
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: green;
    transition: all 0.5s 0s ease;
}

#percentage{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 99;
}
</style>