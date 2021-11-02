<template>
    <app-layout>
        <template #header>
            {{course.title}}
        </template>
        <div class="py-3">
            <p class="text-2xl py-4">Episode N°{{currentKey+1}} : {{course.episodes[currentKey].title}}</p>
            <iframe class="w-full h-screen" :src="course.episodes[currentKey].video_url" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <p class="text-gray-500 py-4">{{course.episodes[currentKey].description}}</p>
                
            <p class="text-sm text-gray-500"><i class="fa fa-user"></i> {{course.user.name}}</p>

            <progress-bar :watchedEp="watched" :courseEp="course.episodes"/>

            <div class="mt-6">
                <ul v-for="(episode, index) in course.episodes" v-bind:key="index">
                    <li class="mt-6 flex justify-between">
                        <div>
                            Episode N°{{index + 1}} - {{episode.title}}
                            <button class="text-gray-500 focus:text-indigo-500 outline-none" @click="switchEpisode(index)">Voir l'episode</button>
                        </div>

                        <progress-button :episodeId="episode.id" :watched="watched"/>

                    </li>
                </ul>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout.vue'
import ProgressButton from './ProgressButton.vue'
import ProgressBar from './ProgressBar.vue'

export default {
    
    components: {
        AppLayout,
        ProgressButton,
        ProgressBar,
    },

    props : ['course', 'watched'],

    data(){
        return {
            currentKey: 0,
        }
    },

    methods: {
        switchEpisode(index){
            this.currentKey = index;

            window.scrollTo({
                top: 0,
                left:0,
                behavior: 'smooth'
            })
        }
    },

    mounted(){
    }
}
</script>

<style>

</style>