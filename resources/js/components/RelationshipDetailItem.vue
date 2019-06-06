<template>
    <div class="card shadow-md mb-4">
        <div class="bg-30 flex p-2 border-b border-40">
                        <span>
                            <button class="btn btn-default btn-icon btn-white mr-3 p-1" v-if="collapsed" @click="collapsed=false">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"/></svg>
                            </button>
                            <button class="btn btn-default btn-icon btn-white mr-3 p-1" v-else @click="collapsed=true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17 11a1 1 0 0 1 0 2H7a1 1 0 0 1 0-2h10z"/></svg>
                            </button>
                        </span>
            <span class="font-normal text-90 py-2 px-2">
                            {{ label }} {{id+1}}
                        </span>
        </div>
        <transition name="slide-fade">
            <div v-if="! collapsed">
                <div class="flex p-2 border-b border-40" v-for="(parameter, attrib) in value" :key="attrib">
                    <div class="w-1/4 py-2 px-2"><h4 class="font-normal text-80">{{attrib}}</h4></div>
                    <div class="w-3/4 py-2 px-2">
                        {{parameter}}
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: "RelationshipDetailItem",

        props:{
            'value': Object,
            'collapsed':{
                type: Boolean,
                default: false
            },
            'label': String,
            'id': Number
        },
    }
</script>
