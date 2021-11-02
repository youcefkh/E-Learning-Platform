<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier votre formation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- aler message -->
                    <div v-if="$page.props.flash.message" class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3" role="alert">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
                        <p>{{ $page.props.flash.message }}</p>
                    </div>

                    <div class="w-full">
                        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8" @submit.prevent="submit">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                                    Titre de la formation
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" name="title" type="text" placeholder="Titre" v-model="courseData.title">
                                <div class="text-red-500 mb-4" v-if="errors.title">{{ errors.title }}</div>

                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                    Description de la formation
                                </label>
                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" placeholder="Description" v-model="courseData.description"></textarea>
                                <div class="text-red-500 mb-4" v-if="errors.description">{{ errors.description }}</div>

                            </div>
                            <div class="mb-4">
                                <h2 class="text-2xl mb-4">Episodes de la formation</h2>

                                <div class="ml-4 mb-4 p-4 border-2 rounded-md" v-for="(episode, index) in courseData.episodes" :key="index">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-xl mb-4"> Episode N°{{index + 1}}</h3>
                                        <button class="text-red-600 text-2xl float-right" type="button" v-if="index !== 0" @click="deleteEp(index)"><i class="fas fa-times-circle"></i></button>
                                    </div>

                                    <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" :for="'title'+index">
                                        Titre
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" :id="'title'+index" name="title" type="text" placeholder="Titre" v-model="courseData.episodes[index].title">
                                    <div class="text-red-500 mb-4" v-if="errors['episodes.'+index+'.title']">{{ errors['episodes.'+index+'.title'] }}</div>

                                    <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" :for="'description'+index">
                                        Description
                                    </label>
                                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" :id="'description'+index" name="description" type="text" placeholder="Description" v-model="courseData.episodes[index].description"></textarea>
                                    <div class="text-red-500 mb-4" v-if="errors['episodes.'+index+'.description']">{{ errors['episodes.'+index+'.description'] }}</div>


                                    <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" :for="'video_url'+index">
                                        Video URL
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" :id="'video_url'+index" name="video_url" type="text" placeholder="ex: wwww.youtube.com/?watch:1268844dd" v-model="courseData.episodes[index].video_url">
                                    <div class="text-red-500 mb-4" v-if="errors['episodes.'+index+'.video_url']">{{ errors['episodes.'+index+'.video_url'] }}</div>


                                </div>
                                
                                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" @click="addEpisode" type="button">
                                    <i class="fa fa-plus"></i> Ajouter un épisode
                                </button>
                            </div>
                            
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Modifier ma formation
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '../../Layouts/AppLayout.vue'
    import { Inertia } from '@inertiajs/inertia'

    export default defineComponent({
        components: {
            AppLayout,
        },

        props: ['course', 'errors'],

        data(){
            return{
                courseData: this.course
            }
        },

        methods: {
            submit(e) {
                Inertia.patch(route('courses.update', this.courseData.id), this.courseData)
                //this.resetForm()
            },
            addEpisode(e){
                this.courseData.episodes.push({title: null, description: null, video_url: null})
                if(this.courseData.episodes.length == 15 ){
                    e.currentTarget.parentNode.removeChild(e.currentTarget);
                }
            },
            deleteEp(index){
                this.courseData.episodes.splice(index, 1);
            },
            resetForm(){
                this.courseData = {
                    title: null,
                    description: null,
                    episodes: [
                        {title: null, description: null, video_url: null},
                    ]
                }
            }
        },

        mounted(){
            if(this.errors){
                console.log(this.errors)
            }
        }
    })
</script>
