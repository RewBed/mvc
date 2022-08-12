<template>
   <div>
     <h1>demo</h1>
   </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'

import axios from "axios";

export default defineComponent({
    name: 'LineChart',
    mounted() {

    },
    methods: {},
    data() {
        return {
            data: []
        }
    }
})
</script>

<style>
#app {
    color: #2ecc71
}
</style>
