<template>
    <app-layout>
        <template #header>
            Liste des formations
        </template>
        <div v-for="course in this.courseList.data" v-bind:key="course.id" class="py-6">
            <div class="mx-4 bg-white p-4 rounded shadow">
                <div class="flex justify-between items-start my-3">
                    <h1 class="text-4xl max-w-2xl">{{course.title}}</h1>
                    <p class="text-sm text-gray-500">{{course.episodes_count}} Episodes | {{course.participants}} Participant<span v-if="parseInt(course.participants) > 1">s</span></p>
                </div>
                <p class="text-gray-500">{{course.description}}</p>
                <div class="font-semibold text-gray-500 mt-6" v-if="course.total_duration"><i class="fa fa-clock"></i> {{convert(course.total_duration)}}</div>
                <div class="flex justify-between items-end">
                    <div>
                        <a :href="route('courses.show', course.id)" class="bg-indigo-500 inline-block px-2 py-1 mt-3 mr-4 text-sm rounded hover:bg-indigo-700 text-white ">Accéder à la formation</a>

                        <a :href="route('courses.edit', course.id)" v-if="course.edit" class="bg-gray-500 inline-block px-2 py-1 mt-3 text-sm rounded hover:bg-gray-700 text-white">Modifier la formation</a>
                    </div>
                    
                    <p class="text-sm text-gray-500"><i class="fa fa-user"></i> {{course.user.name}}</p>
                </div>
            </div>
        </div>

        <div class="my-4">
            <Link class="px-2 py-1 mx-4 rounded text-white" :class="link.active ? 'bg-yellow-600' : 'bg-indigo-500'" :href="link.url" v-for="(link, index) in courseList.links" :key="link.label">
            
                <span v-if="index==0">Previous</span>
                <span v-else-if="index==courseList.links.length - 1">Next</span>
                <span v-else>{{link.label}}</span>
                
            </Link>

        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        AppLayout,
        Link
    },

    props : ['courses'],

    data(){
        return {
            courseList: this.courses,
        }
    },

    methods: {
        convert(time){
            let hours = Math.floor(time / 3600);
            let minutes = Math.floor(time / 60) - (hours * 60);
            let seconds = time % 60;

            return hours.toString().padStart(2, 0) + ':' + minutes.toString().padStart(2, 0) + ':' + seconds.toString().padStart(2, 0);
        }
    },

    mounted(){
        console.log(this.courses)
    }
}
</script>

<style>

</style>