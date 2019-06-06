<template>
    <div class="flex border-b border-40">
        <div class="w-1/4 py-4">
            <slot>
                <h4 class="font-normal text-80">{{ field.name }}</h4>
            </slot>
        </div>
        <div class="w-3/4 py-4">
            <div>
                <div class="card shadow-md mb-4" v-for="(item, key) in field.value" :key="key">
                    <div class="bg-30 flex p-2 border-b border-40">
                        <span>
                            <button class="btn btn-default btn-icon btn-white mr-3 p-1" v-if="getCollapsed(key)" @click="setCollapsed(key, false)">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"/></svg>
                            </button>
                            <button class="btn btn-default btn-icon btn-white mr-3 p-1" v-else @click="setCollapsed(key, true)">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17 11a1 1 0 0 1 0 2H7a1 1 0 0 1 0-2h10z"/></svg>
                            </button>
                        </span>
                        <span class="font-normal text-90 py-2 px-2">
                            {{ field.name }} {{key+1}}
                        </span>
                    </div>
                    <transition name="slide-fade">
                        <div v-if="! getCollapsed(key)">
                            <div class="flex p-2 border-b border-40" v-for="(parameter, attrib) in item" :key="attrib">
                                <div class="w-1/4 py-2 px-2"><h4 class="font-normal text-80">{{attrib}}</h4></div>
                                <div class="w-3/4 py-2 px-2">
                                    {{parameter}}
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data:function(){
        return {
            collapsed: []
        }
    },

    mounted:function(){
        this.field.value.forEach((val, index)=>{
            this.collapsed[index] = false;
        });
    },

    methods:{
        getCollapsed:function(key){
            return this.collapsed.hasOwnProperty(key) && (this.collapsed[key]===true);
        },

        setCollapsed:function(key, value){
            this.collapsed[key] = value;
        }
    },
}
</script>
