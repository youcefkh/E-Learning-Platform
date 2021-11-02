<template>
    <div>
        <button class="py-1 px-2 text-black rounded" :class="this.isWatchedEpisode() ? 'bg-gray-500' : 'bg-green-500 '" @click="toggleProgress()">
            <span v-if="this.isWatchedEpisode()">Reprendre</span>
            <span v-else>Terminé</span>
        </button>
    </div>
</template>

<script>
import axios from 'axios'
//import {eventBus} from '../../eventBus'
export default {
    props: ['episodeId', 'watched'],

    data(){
        return{
            watchedEp: this.watched
        }
    },

    methods: {
        toggleProgress(){
            axios.post(route("toggleProgress"), {
                episodeId: this.episodeId
            })
            .then(resp => {
                if(resp.status == 200){
                    this.watchedEp = resp.data;
                    eventBus.emit('toggleProgress', this.watchedEp)
                }
            })
            .catch(error => console.log(error))
        },

        isWatchedEpisode(){
            return this.watchedEp.find(episode => episode.id === this.episodeId) ? true : false;
        }
    },

    mounted(){
    }
}
</script>

<style>

</style>